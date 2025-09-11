<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Order;
use Carbon\Carbon;

class CashierDashboardController extends Controller
{
   public function index()
    {
        // Today’s sales (sum of today’s sales total_amount)
        $todaySales = Sale::whereDate('sale_date', Carbon::today())
                          ->sum('total_amount');

         return response()->json([
            'today_sales' => $todaySales,
        ]);

        // 'pending_orders' => $pendingOrders
        // Pending orders (status = "pending")
        //$pendingOrders = Order::where('status', 'pending')->count();

    }
}
