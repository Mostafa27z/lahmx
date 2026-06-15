<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');

        $categories = $this->productRepository->getAllCategories();
        $products = $this->productRepository->getActiveProducts($search, $categoryId, 12);

        return view('front.products.index', compact('products', 'categories', 'search', 'categoryId'));
    }

    public function show(string $slug)
    {
        $product = $this->productRepository->findBySlug($slug);
        
        // Fetch related products in the same category
        $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_available', true)
            ->limit(4)
            ->get();

        return view('front.products.show', compact('product', 'relatedProducts'));
    }
}
