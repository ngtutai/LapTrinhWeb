<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        /** @var Builder $query */
        $query = Product::with('category');

        if ($q !== '') {
            $this->applySearch($query, $q);
        }

        /** @var LengthAwarePaginator $products */
        $products = $query->latest('id')
            ->paginate(9)
            ->appends($request->query());

        $categories = Category::orderBy('name')->get();

        return view('shop.home', compact('products', 'categories', 'q'));
    }

    public function byCategory(string $slug, Request $request): View
    {
        $q = trim((string) $request->query('q', ''));
        $category = Category::where('slug', $slug)->firstOrFail();

        /** @var Relation|Builder $query */
        $query = $category->products()->with('category');

        if ($q !== '') {
            $this->applySearch($query, $q);
        }

        $products   = $query->latest('id')->paginate(12)->appends($request->query());
        $categories = Category::orderBy('name')->get();

        return view('shop.home', compact('products', 'categories', 'category', 'q'));
    }

    public function show(string $slug): View
    {
        $product = Product::where('slug', $slug)->firstOrFail();
        return view('shop.product', compact('product'));
    }

    public function suggest(Request $request): JsonResponse
    {
        $q = trim((string) $request->query('q', ''));
        if ($q === '') {
            return response()->json(['products' => [], 'categories' => []]);
        }

        $pQuery = Product::query();
        $this->applySearch($pQuery, $q);

        $products = $pQuery->latest('id')->limit(5)->get()->map(function ($p) {
            $thumb = $p->thumbnail
                ? asset('storage/'.$p->thumbnail)
                : asset('images/placeholder-product.jpg');

            return [
                'name'  => $p->name,
                'price' => isset($p->price) ? number_format($p->price, 0, ',', '.').' đ' : null,
                'thumb' => $thumb,
                'url'   => route('product.show', $p->slug),
            ];
        })->values();

        $like = '%'.$q.'%';
        $categories = Category::where('name', 'like', $like)
            ->orderBy('name')->limit(5)->get()
            ->map(fn($c) => [
                'name' => $c->name,
                'url'  => route('category.show', $c->slug),
            ])->values();

        return response()->json(compact('products', 'categories'));
    }

    protected function applySearch(Builder|Relation $query, string $term): void
    {
        $like = '%'.$term.'%';
        $candidates = ['name','description','short_description','summary','content','details','brand','sku'];
        $columns = array_values(array_filter($candidates, fn($col) => Schema::hasColumn('products', $col)));

        if (empty($columns)) {
            if (Schema::hasColumn('products', 'name')) {
                $columns = ['name'];
            } else {
                return;
            }
        }

        $query->where(function ($w) use ($columns, $like) {
            foreach ($columns as $i => $col) {
                $i === 0 ? $w->where($col, 'like', $like) : $w->orWhere($col, 'like', $like);
            }
        });
    }
}
