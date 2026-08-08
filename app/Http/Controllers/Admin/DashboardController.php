<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard index.
     */
    public function index(Request $request)
    {
        // Statistics
        $totalOrdersCount = Order::count();
        $totalRevenue = Order::sum('total_amount');
        $completedRevenue = Order::where('status', 'completed')->sum('total_amount');
        $pendingCount = Order::where('status', 'pending')->count();
        $preparingCount = Order::where('status', 'preparing')->count();
        $completedCount = Order::where('status', 'completed')->count();

        // Order list filtering
        $statusFilter = $request->status;
        $ordersQuery = Order::with('items')->latest();

        if ($statusFilter && in_array($statusFilter, ['pending', 'preparing', 'completed'])) {
            $ordersQuery->where('status', $statusFilter);
        }

        $orders = $ordersQuery->paginate(15);

        return view('admin.dashboard', compact(
            'totalOrdersCount',
            'totalRevenue',
            'completedRevenue',
            'pendingCount',
            'preparingCount',
            'completedCount',
            'orders',
            'statusFilter'
        ));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,preparing,completed',
        ]);

        $oldStatus = $order->status;
        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', "Order #{$order->order_number} status updated from '" . ucfirst($oldStatus) . "' to '" . ucfirst($request->status) . "'!");
    }
}
