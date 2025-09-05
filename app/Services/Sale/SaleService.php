<?php

namespace App\Services\Sale;

use LaravelEasyRepository\BaseService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

interface SaleService extends BaseService{

    // Write something awesome :)
    public function recordSale(array $data);

     public function getAllSales(int $perPage = 15): AnonymousResourceCollection;

    public function getSalesByDate(string $date, int $perPage = 15): AnonymousResourceCollection;

    public function getReceiptByDate(string $date);

      public function getSaleById(int $id);
}
