<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products   = Product::with('category')->latest()->paginate(15);
        $categories = Category::orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'brand'       => 'nullable|string|max:100',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'specs'       => 'sometimes|array',
            'specs.*'     => 'nullable|string|max:255',
        ]);

        $data['slug'] = Str::slug($data['name']).'-'.Str::random(4);

        if ($req->hasFile('thumbnail')) {
            $data['thumbnail'] = $req->file('thumbnail')->store('products', 'public');
        }

        if (!array_key_exists('specs', $data)) $data['specs'] = null;

        Product::create($data);
        return redirect()->route('admin.products.index')->with('success', 'Đã tạo sản phẩm');
    }

    public function update(Request $req, Product $product)
    {
        $data = $req->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'brand'       => 'nullable|string|max:100',
            'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',
            'specs'       => 'sometimes|array',
            'specs.*'     => 'nullable|string|max:255',
        ]);

        if ($product->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']).'-'.Str::random(4);
        }

        if ($req->hasFile('thumbnail')) {
            if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $req->file('thumbnail')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm');
    }

    public function destroy(Product $product)
    {
        if ($product->thumbnail && Storage::disk('public')->exists($product->thumbnail)) {
            Storage::disk('public')->delete($product->thumbnail);
        }
        $product->delete();
        return back()->with('success', 'Đã xoá sản phẩm');
    }
}
