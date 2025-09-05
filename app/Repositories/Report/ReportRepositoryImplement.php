<?php

namespace App\Repositories\Report;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class ReportRepositoryImplement extends Eloquent implements ReportRepository
{
    protected SaleItem $model;

    public function __construct(SaleItem $model)
    {
        $this->model = $model;
    }

    public function salesSummary(string $startDate, string $endDate)
    {
        return $this->model
            ->select(
                'products.id',
                'products.name',
                'products.sku',
                DB::raw('SUM(sale_items.quantity) as sold_quantity'),
                DB::raw('SUM(sale_items.subtotal) as revenue'),
                DB::raw('MAX(products.stock_qty) as remaining_stock')
            )
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->whereBetween('sales.sale_date', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->groupBy('products.id', 'products.name', 'products.sku')
            ->get();
    }
}
