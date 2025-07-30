<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function currentOrders()
    {
        $orders = Order::with('orderItems')->get();
        return view('admin.order.current_orders', compact('orders'));
    }

    public function changeStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'status' => 'required|integer',
        ]);
        $order = Order::findOrFail($request->order_id);
        switch ($request->status) {
            case 1:
                $order->is_processed_at = now();
                break;
            case 2:
                $order->is_shipped_at = now();
                break;
            case 3:
                $order->is_done_at = now();
                break;
            case 4:
                $order->is_cancelled_at = now();
                break;
        }
        $order->update([
            'status' => $request->status,
        ]);
        return back()->with('success', 'Status order berhasil diubah.');
    }
    public function history_orders()
    {
        $orders = Order::with('orderItems')->get();
        return view('admin.order.history_orders', compact('orders'));
    }
}
