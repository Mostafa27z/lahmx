<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\CartRepository;
use App\Services\OrderService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    protected CartRepository $cartRepository;
    protected OrderService $orderService;

    public function __construct(CartRepository $cartRepository, OrderService $orderService)
    {
        $this->cartRepository = $cartRepository;
        $this->orderService = $orderService;
    }

    public function index()
    {
        $cart = $this->cartRepository->getOrCreateCart();
        
        if ($cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'سلتك فارغة، الرجاء إضافة منتجات أولاً.');
        }

        return view('front.checkout.index', compact('cart'));
    }

    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
            'payment_method' => 'required|in:visa,tabby,tamara',
            'notes' => 'nullable|string',
        ]);

        $data = $request->only(['customer_name', 'phone', 'city', 'address', 'payment_method', 'notes']);
        
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        try {
            $order = $this->orderService->placeOrder($data);
            return redirect()->route('orders.show', $order->id)
                ->with('success', 'تم تسجيل طلبك بنجاح وعملية الدفع اكتملت.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}
