<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
class OrderController extends Controller
{
    //

    public function showOrders()
    {
        $orders = Order::with('items.product')->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    public function deleteOrderItem($itemId)
    {
        // Find the order item by its ID

        $order = \App\Models\Order::findOrFail($itemId);




        // Delete the order item
        $order->delete();


        return redirect()->route('admin.orders')->with('success', 'Order item deleted successfully.');
    }
}
