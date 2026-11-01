<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminOrderController extends Controller
{
    public const STATUSES = ['pending', 'shipped', 'delivered', 'cancelled'];

    public function index(Request $request)
    {
        $status = $request->query('status');
        $status = in_array($status, self::STATUSES, true) ? $status : null;
        $search = trim((string) $request->query('search', ''));

        $orders = Order::with('user')
            ->withSum('items', 'quantity')
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search !== '', function ($q) use ($search) {
                $q->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhere('shipping_name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        $counts = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.orders.index', [
            'orders'   => $orders,
            'status'   => $status,
            'search'   => $search,
            'statuses' => self::STATUSES,
            'counts'   => $counts,
        ]);
    }

    public function show(Order $order)
    {
        $order->load('items', 'user');

        return view('admin.orders.show', [
            'order'    => $order,
            'statuses' => self::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => ['required', 'in:' . implode(',', self::STATUSES)],
        ]);

        $new = $data['status'];

        if ($order->status === 'cancelled') {
            return back()->with('error', 'A cancelled order cannot be changed.');
        }

        if ($new === $order->status) {
            return back();
        }

        DB::transaction(function () use ($order, $new) {
            // Cancelling puts the items back in stock
            if ($new === 'cancelled') {
                foreach ($order->items as $item) {
                    $product = Product::lockForUpdate()->find($item->product_id);

                    if ($product) {
                        $product->increment('stock', $item->quantity);
                    }
                }
            }

            $order->update(['status' => $new]);
        });

        return back()->with('success', "Order {$order->order_number} is now {$new}.");
    }
}