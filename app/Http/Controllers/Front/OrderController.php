<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected OrderRepository $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'يجب تسجيل الدخول لمشاهدة طلباتك.');
        }

        $orders = $this->orderRepository->getOrdersForUser(Auth::id());
        return view('front.orders.index', compact('orders'));
    }

    public function show(int $id)
    {
        $order = $this->orderRepository->findById($id);

        if (!$order) {
            abort(404, 'الطلب غير موجود.');
        }

        // Guests can view their order directly after checkout, but other orders are protected.
        if (Auth::check() && $order->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'غير مصرح لك بمشاهدة هذا الطلب.');
        }

        if (!Auth::check() && $order->user_id !== null) {
            abort(403, 'يجب تسجيل الدخول لمشاهدة هذا الطلب.');
        }

        return view('front.orders.show', compact('order'));
    }
}
