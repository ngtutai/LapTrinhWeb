<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // GET /admin/orders
    public function index()
    {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    // GET /admin/orders/{order}
    // Route Model Binding sẽ bơm Order $order vào đây
    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    // PUT /admin/orders/{order}
    public function update(Request $req, Order $order)
    {
        $req->validate([
            'status' => 'required|in:pending,paid,shipped,cancelled',
        ]);

        $order->update(['status' => $req->string('status')]);

        return back()->with('success', 'Đã cập nhật trạng thái');
    }
}
