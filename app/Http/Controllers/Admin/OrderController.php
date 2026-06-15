<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = Order::with('user');

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query->latest()->paginate(10);
        return view('admin.orders.index', compact('orders', 'status'));
    }

    public function show(int $id)
    {
        $order = Order::with(['items.product', 'payment'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
        ]);

        $order = Order::findOrFail($id);
        $order->update([
            'status' => $request->status,
            'payment_status' => $request->payment_status,
        ]);

        return redirect()->route('admin.orders.show', $order->id)->with('success', 'تم تحديث حالة الطلب بنجاح.');
    }
}
