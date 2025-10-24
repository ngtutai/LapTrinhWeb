<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

// app/Http/Controllers/CartController.php
class CartController extends Controller
{
    protected function cart()
    {
        return session()->get('cart', []);
    }

    protected function save($cart)
    {
        session(['cart' => $cart]);
    }

    public function index()
    {
        $cart = $this->cart();
        $items = [];
        $total = 0;
        foreach ($cart as $pid => $qty) {
            $p = Product::find($pid);
            if (! $p) {
                continue;
            }
            $qty = min($qty, $p->stock);
            $items[] = ['product' => $p, 'qty' => $qty, 'line' => $p->price * $qty];
            $total += $p->price * $qty;
        }

        return view('shop.cart', compact('items', 'total'));
    }

    public function add(Product $product, Request $req)
    {
        $qty = max(1, (int) $req->input('qty', 1));
        $cart = $this->cart();
        $cart[$product->id] = min(($cart[$product->id] ?? 0) + $qty, $product->stock);
        $this->save($cart);

        return redirect()->route('cart.index')->with('success', 'Đã thêm vào giỏ');
    }

    public function update(Product $product, Request $req)
    {
        $qty = (int) $req->input('qty', 1);
        $cart = $this->cart();
        if ($qty <= 0) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = min($qty, $product->stock);
        }
        $this->save($cart);

        return back();
    }

    public function remove(Product $product)
    {
        $cart = $this->cart();
        unset($cart[$product->id]);
        $this->save($cart);

        return back();
    }
}
