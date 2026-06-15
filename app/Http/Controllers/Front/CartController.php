<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Repositories\CartRepository;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartRepository $cartRepository;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function index()
    {
        $cart = $this->cartRepository->getOrCreateCart();
        return view('front.cart.index', compact('cart'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock_quantity < $request->quantity) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة في المخزون.'], 400);
            }
            return back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون.');
        }

        $this->cartRepository->addItem($product, $request->quantity);

        if ($request->wantsJson()) {
            $cart = $this->cartRepository->getOrCreateCart();
            return response()->json([
                'success' => true,
                'message' => 'تم إضافة المنتج إلى السلة.',
                'items_count' => $cart->items_count,
                'total' => $cart->total,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'تم إضافة المنتج إلى السلة.');
    }

    public function update(Request $request, int $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $item = \App\Models\CartItem::findOrFail($itemId);
        $product = $item->product;

        if ($request->quantity > $product->stock_quantity) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة في المخزون.'], 400);
            }
            return back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون.');
        }

        $updatedItem = $this->cartRepository->updateItemQuantity($itemId, $request->quantity);
        $cart = $this->cartRepository->getOrCreateCart();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث السلة.',
                'items_count' => $cart->items_count,
                'total' => $cart->total,
                'item_subtotal' => $updatedItem ? $updatedItem->subtotal : 0,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'تم تحديث السلة.');
    }

    public function remove(Request $request, int $itemId)
    {
        $this->cartRepository->removeItem($itemId);
        $cart = $this->cartRepository->getOrCreateCart();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم حذف المنتج من السلة.',
                'items_count' => $cart->items_count,
                'total' => $cart->total,
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'تم حذف المنتج من السلة.');
    }
}
