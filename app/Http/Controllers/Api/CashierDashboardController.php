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
       // ✅ Total quantity of products sold today
       $todayProductSales = Sale::whereDate('sale_date', Carbon::today())
                                ->with('saleItems')
                                ->get()
                                ->flatMap->saleItems
                                ->sum('quantity');

       // ✅ Total sales amount today (₦)
       $todaySalesAmount = Sale::whereDate('sale_date', Carbon::today())
                               ->sum('total_amount');

       return response()->json([
           'today_product_sales' => $todayProductSales,
           'today_sales_amount'  => $todaySalesAmount,
       ]);
   }


}
