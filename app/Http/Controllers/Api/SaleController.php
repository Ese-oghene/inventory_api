<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Sale\SaleService;

class SaleController extends Controller
{

     protected SaleService $saleService;

   public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

     /**
     * Create a new sale
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string|exists:products,sku',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:cash,transfer,card', // ✅ validate
        ]);

        return $this->saleService->recordSale($validated)->toJson();
    }

     /**
     * List all sales (paginated)
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        return $this->saleService->getAllSales($perPage);
    }

     /**
     * Get single sale by ID (receipt)
     */
    public function show(int $id)
    {
        return $this->saleService->getSaleById($id);
    }



    /**
     * Get sales for a specific date (paginated)
     * 
     */
    public function salesByDate(Request $request, string $date)
    {
        $perPage = $request->get('per_page', 5);
        return $this->saleService->getSalesByDate($date, $perPage);
    }

    /**
     * Get all sales for a date (for receipts / reports, non-paginated)
     */
    public function receiptsByDate(string $date)
    {
        return $this->saleService->getReceiptByDate($date);
    }

}
