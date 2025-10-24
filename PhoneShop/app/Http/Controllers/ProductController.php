<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

// app/Http/Controllers/ProductController.php
class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(9);
        $categories = Category::orderBy('name')->get();

        return view('shop.home', compact('products', 'categories'));
    }

    public function byCategory($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $products = $category->products()->paginate(12);
        $categories = Category::orderBy('name')->get();

        return view('shop.home', compact('products', 'categories', 'category'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->firstOrFail();

        return view('shop.product', compact('product'));
    }
}
