<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    /**
     * Show the wishlist page.
     * Product IDs live in localStorage — the view fetches them via JS → /wishlist/fetch
     */
    public function index()
    {
        return view('front.wishlist.index');
    }

    /**
     * Accept an array of product IDs (from localStorage) and return matching products as JSON.
     * POST /wishlist/fetch
     */
    public function fetch(Request $request)
    {
        $ids = $request->input('ids', []);

        if (empty($ids) || !is_array($ids)) {
            return response()->json([]);
        }

        $products = Product::whereIn('id', array_map('intval', $ids))
            ->get(['id', 'name', 'slug', 'image', 'price', 'discount_price', 'weight', 'stock_quantity', 'is_available']);

        return response()->json($products->map(function ($p) {
            return [
                'id'             => $p->id,
                'name'           => $p->name,
                'slug'           => $p->slug,
                'image_url'      => $p->image ? asset('storage/' . $p->image) : null,
                'price'          => $p->price,
                'discount_price' => $p->discount_price,
                'active_price'   => $p->discount_price ?? $p->price,
                'weight'         => $p->weight,
                'in_stock'       => $p->stock_quantity > 0,
                'product_url'    => route('products.show', $p->slug),
            ];
        }));
    }
}
