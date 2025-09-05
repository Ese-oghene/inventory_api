<?php

namespace App\Repositories\Sale;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Sale;
use Illuminate\Pagination\LengthAwarePaginator;

class SaleRepositoryImplement extends Eloquent implements SaleRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected Sale $model;

    public function __construct(Sale $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)

     public function createSale(array $data): Sale
    {
        return Sale::create($data);
    }

    public function getByDate(string $date, int $perPage = 15): LengthAwarePaginator
    {
        return Sale::with('saleItems.product', 'cashier')
            ->whereDate('sale_date', $date)
            ->orderBy('sale_date', 'desc')
            ->paginate($perPage);
    }

    public function getAll(int $perPage = 15): LengthAwarePaginator
    {
        return Sale::with('saleItems.product', 'cashier')
            ->orderBy('sale_date', 'desc')
            ->paginate($perPage);
    }

     public function addSaleItem($sale, array $data)
    {
        return $sale->saleItems()->create($data);
    }

    public function updateSaleTotal($sale, float $totalAmount)
    {
        $sale->update(['total_amount' => $totalAmount]);
        return $sale;
    }

    public function getSaleById(int $id)
    {
        return $this->model->with('saleItems.product', 'cashier')->find($id);
    }
}
