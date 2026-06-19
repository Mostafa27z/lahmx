<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if ($request->filled('discount_price') && $request->filled('price')) {
            if ($request->discount_price >= $request->price) {
                $request->merge(['discount_price' => null]);
            }
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'weight' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'is_available' => 'nullable|boolean',
        ], [
            'discount_price.lt' => 'يجب أن يكون سعر التخفيض أقل من السعر الأساسي للمنتج.',
            'discount_price.min' => 'سعر التخفيض لا يمكن أن يكون أقل من صفر.',
            'price.min' => 'السعر الأساسي لا يمكن أن يكون أقل من صفر.',
        ]);

        $slug = Str::slug($request->name);
        if (empty(trim($slug, '-'))) {
            $slug = str_replace(' ', '-', $request->name);
        }

        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        $imagesPaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagesPaths[] = $file->store('products/gallery', 'public');
            }
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'weight' => $request->weight,
            'image' => $imagePath,
            'images' => $imagesPaths,
            'stock_quantity' => $request->stock_quantity,
            'is_available' => $request->has('is_available'),
        ]);

        return redirect()->route('admin.products.index')->with('success', 'تم إضافة المنتج بنجاح.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        if ($request->filled('discount_price') && $request->filled('price')) {
            if ($request->discount_price >= $request->price) {
                $request->merge(['discount_price' => null]);
            }
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'weight' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:20480',
            'is_available' => 'nullable|boolean',
        ], [
            'discount_price.lt' => 'يجب أن يكون سعر التخفيض أقل من السعر الأساسي للمنتج.',
            'discount_price.min' => 'سعر التخفيض لا يمكن أن يكون أقل من صفر.',
            'price.min' => 'السعر الأساسي لا يمكن أن يكون أقل من صفر.',
        ]);

        $slug = Str::slug($request->name);
        if (empty(trim($slug, '-'))) {
            $slug = str_replace(' ', '-', $request->name);
        }

        if ($slug !== $product->slug) {
            $originalSlug = $slug;
            $count = 1;
            while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $product->slug = $slug;
        }

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $product->image = $request->file('image')->store('products', 'public');
        }

        // Handle individual gallery image deletions
        $currentImages = is_array($product->images) ? $product->images : [];
        if ($request->has('delete_gallery') && is_array($request->delete_gallery)) {
            foreach ($request->delete_gallery as $pathToDelete) {
                Storage::disk('public')->delete($pathToDelete);
                $currentImages = array_values(array_filter($currentImages, fn($img) => $img !== $pathToDelete));
            }
        }

        // Append newly uploaded gallery images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $currentImages[] = $file->store('products/gallery', 'public');
            }
        }

        $product->images = $currentImages;

        $product->category_id = $request->category_id;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->weight = $request->weight;
        $product->stock_quantity = $request->stock_quantity;
        $product->is_available = $request->has('is_available');
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'تم تحديث المنتج بنجاح.');
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        if ($product->images && is_array($product->images)) {
            foreach ($product->images as $oldImg) {
                Storage::disk('public')->delete($oldImg);
            }
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'تم حذف المنتج بنجاح.');
    }
}
