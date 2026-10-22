<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::withSum('items', 'quantity')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('orders.index', ['orders' => $orders]);
    }

    public function show(Order $order)
    {
        // A user may only see their own orders
        abort_if($order->user_id !== Auth::id(), 403);

        $order->load('items');

        return view('orders.show', ['order' => $order]);
    }
}