<?php

namespace App\Http\Controllers;

use App\Models\Resi;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Http\Request;
use App\Helpers\NotificationHelper;
use App\Helpers\AdminActivityHelper;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function currentOrders()
    {
        $orders = Order::with('orderItems', 'reseller', 'resi', 'resi.resiSource')
            ->whereNotIn('status', [3, 4])
            ->get();
        return view('admin.order.current_orders', compact('orders'));
    }
    public function downloadResi(Resi $resi)
    {
        try {
            $response = Http::get($resi->file_path); // ambil file dari Cloudinary

            return response($response->body(), 200)
                ->header('Content-Type', $response->header('Content-Type'))
                ->header('Content-Disposition', 'attachment; filename="' . $resi->file_name . '"');
         } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengunduh resi pengiriman. Silakan coba lagi.');
        }
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
        NotificationHelper::notifyReseller($order->reseller, 'Status Order Diperbarui', 'Status order Anda telah diperbarui menjadi: ' . $order->status_name, route('order.history'));
        // Log aktivitas admin
        AdminActivityHelper::log('UPDATE', 'orders', $order->id, 'Mengubah status order: ' . $order->order_code . ' ke status ' . $order->status_name);
        return back()->with('success', 'Status order berhasil diubah.');
    }
    public function history_orders()
    {
        $orders = Order::with('orderItems', 'reseller', 'resi')->get();
        return view('admin.order.history_orders', compact('orders'));
    }
    public function orderHistory()
    {
        $orders = Order::where('reseller_id', auth()->id())
            ->with('orderItems.variant.product', 'rating', 'resi')
            ->get();
        return view('store.profile.order_history', compact('orders'));
    }
    public function orderDetail($order_code)
    {
        $order = Order::where('order_code', $order_code)->first();
        if (!$order) {
            return back()->with('error', 'Order tidak ditemukan atau sudah dibatalkan.');
        }
        $review = Rating::where('order_id', $order->id)->latest()->first();
        $order->shops = Order::where('order_code', $order_code)->with('orderItems.variant.product')->get();
        return view('store.profile.detail_order', compact('order', 'review'));
    }
}
