<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductRepository
{
    public function getActiveProducts(string $search = null, int $categoryId = null, int $perPage = 12): LengthAwarePaginator
    {
        $query = Product::with('category')->where('is_available', true);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        return $query->latest()->paginate($perPage);
    }

    public function getFeaturedProducts(int $limit = 6): Collection
    {
        return Product::where('is_available', true)
            ->where('stock_quantity', '>', 0)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getLatestProducts(int $limit = 6): Collection
    {
        return Product::where('is_available', true)
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function findBySlug(string $slug): ?Product
    {
        return Product::where('slug', $slug)
            ->where('is_available', true)
            ->firstOrFail();
    }

    public function getAllCategories(): Collection
    {
        return Category::where('is_active', true)->get();
    }
}
