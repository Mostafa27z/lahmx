<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Repositories\ProductRepository;

class HomeController extends Controller
{
    protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index()
    {
        $categories = $this->productRepository->getAllCategories();
        $featuredProducts = $this->productRepository->getFeaturedProducts(8);
        $latestProducts = $this->productRepository->getLatestProducts(6);

        return view('front.home', compact('categories', 'featuredProducts', 'latestProducts'));
    }

    public function about()
    {
        return view('front.about');
    }
}
