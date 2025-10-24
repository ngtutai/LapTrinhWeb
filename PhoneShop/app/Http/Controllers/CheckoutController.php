<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// app/Http/Controllers/CheckoutController.php
class CheckoutController extends Controller
{
    public function create()
    {
        $cart = session('cart', []);
        abort_if(empty($cart), 400, 'Giỏ trống');

        return view('shop.checkout');
    }

    public function store(Request $req)
    {
        $req->validate(['address' => 'required|string|min:5']);
        $cart = session('cart', []);
        abort_if(empty($cart), 400, 'Giỏ trống');

        DB::transaction(function () use ($req, $cart) {
            $order = Order::create([
                'user_id' => auth()->id(),
                'status' => 'pending',
                'shipping_address' => $req->address,
                'total' => 0,
            ]);
            $total = 0;
            foreach ($cart as $pid => $qty) {
                $p = Product::lockForUpdate()->findOrFail($pid);
                $qty = min($qty, $p->stock);
                if ($qty <= 0) {
                    continue;
                }
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $p->id,
                    'price' => $p->price,
                    'quantity' => $qty,
                ]);
                $total += $p->price * $qty;
                $p->decrement('stock', $qty);
            }
            $order->update(['total' => $total]);
        });

        session()->forget('cart'); // clear cart sau đặt

        return redirect()->route('orders.mine')->with('success', 'Đặt hàng thành công!');
    }

    public function orders()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->with('items.product')->paginate(10);

        return view('shop.orders', compact('orders'));
    }
}
