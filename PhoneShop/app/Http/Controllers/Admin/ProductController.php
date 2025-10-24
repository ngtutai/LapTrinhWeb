<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        // có thể thêm lọc theo q nếu muốn
        $products = Product::with('category')->latest()->paginate(15);
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(Request $req)
    {
        $data = $req->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'brand' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data['slug'] = Str::slug($data['name']).'-'.Str::random(4);

        if ($req->hasFile('thumbnail')) {
            $data['thumbnail'] = $req->file('thumbnail')->store('products', 'public');
        }

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Đã tạo sản phẩm');
    }

    public function update(Request $req, Product $product)
    {
        $data = $req->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'brand' => 'nullable|string|max:100',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($product->name !== $data['name']) {
            $data['slug'] = Str::slug($data['name']).'-'.Str::random(4);
        }

        if ($req->hasFile('thumbnail')) {
            // xóa file cũ nếu có (không bắt buộc)
            if ($product->thumbnail && file_exists(public_path('storage/'.$product->thumbnail))) {
                @unlink(public_path('storage/'.$product->thumbnail));
            }
            $data['thumbnail'] = $req->file('thumbnail')->store('products', 'public');
        }

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Đã cập nhật sản phẩm');
    }

    public function destroy(Product $product)
    {
        if ($product->thumbnail && file_exists(public_path('storage/'.$product->thumbnail))) {
            @unlink(public_path('storage/'.$product->thumbnail));
        }
        $product->delete();

        return back()->with('success', 'Đã xoá sản phẩm');
    }
}
