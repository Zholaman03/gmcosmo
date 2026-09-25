<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
class OrderController extends Controller
{
    public function showOrders(Request $request)
    {
        $filter = $request->string('filter', 'active')->toString();
        $orders = $this->ordersQuery()->when($filter === 'active', fn ($query) => $query->whereIn('status', ['pending', 'processing']))
            ->when($filter === 'unpaid', fn ($query) => $query->whereIn('status', ['pending', 'processing'])->where(function ($query) {
                $query->whereNull('payment_status')->orWhereIn('payment_status', ['unpaid', 'pending', 'processing']);
            }))
            ->when($filter === 'waiting', fn ($query) => $query->where('status', 'pending'))
            ->when($filter === 'cancelled', fn ($query) => $query->where('status', 'cancelled'))
            ->latest()->get();

        $counts = [
            'active' => Order::whereIn('status', ['pending', 'processing'])->count(),
            'unpaid' => Order::whereIn('status', ['pending', 'processing'])->where(function ($query) {
                $query->whereNull('payment_status')->orWhereIn('payment_status', ['unpaid', 'pending', 'processing']);
            })->count(),
            'waiting' => Order::where('status', 'pending')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders', compact('orders', 'filter', 'counts'));
    }

    public function history()
    {
        $orders = $this->ordersQuery()->where('status', 'completed')->latest()->get();

        return view('admin.order-history', compact('orders'));
    }

    private function ordersQuery()
    {
        return Order::with(['items.product', 'deliveryMethod']);
    }

    public function completeOrder(Order $order)
    {
        $order->update(['status' => 'completed']);

        return redirect()->route('admin.orders')->with('success', "Заказ #{$order->id} завершён.");
    }

    public function cancelOrder(Order $order)
    {
        $order->update(['status' => 'cancelled']);

        return redirect()->route('admin.orders')->with('success', "Заказ #{$order->id} отменён.");
    }

    public function deleteOrder(Order $order)
    {
        $order->delete();


        return redirect()->route('admin.orders')->with('success', 'Заказ успешно удалён.');
    }
}
