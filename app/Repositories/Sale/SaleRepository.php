<?php

namespace App\Repositories\Sale;

use LaravelEasyRepository\Repository;
use App\Models\Sale;
use Illuminate\Pagination\LengthAwarePaginator;

interface SaleRepository extends Repository{

    // Write something awesome :)
    public function createSale(array $data): Sale;

    public function getByDate(string $date, int $perPage = 15): LengthAwarePaginator;

    public function getAll(int $perPage = 15): LengthAwarePaginator;

     public function addSaleItem($sale, array $data);

    public function updateSaleTotal($sale, float $totalAmount);

     public function getSaleById(int $id);
}
