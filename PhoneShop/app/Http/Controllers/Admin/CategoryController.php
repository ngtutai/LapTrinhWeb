<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::orderBy('id', 'asc')->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']),
        ]);

        return back()->with('success', 'Đã tạo danh mục');
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate(['name' => 'required|string|max:255']);
        $payload = ['name' => $data['name']];
        if ($category->name !== $data['name']) {
            $payload['slug'] = Str::slug($data['name']);
        }
        $category->update($payload);

        return back()->with('success', 'Đã cập nhật danh mục');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Đã xoá danh mục');
    }
}
