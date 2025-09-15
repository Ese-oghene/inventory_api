<?php

namespace App\Repositories\Product;

use LaravelEasyRepository\Repository;

interface ProductRepository extends Repository{

    // Write something awesome :)

    public function createProduct(array $data);
    public function findProductBySku(string $sku);
    public function getAll();
    public function updateStock(int $productId, int $quantity);

    // ✅ Add this
    public function updateProduct(int $id, array $data);

    // ✅ You might also want deleteProduct for consistency
    public function deleteProduct(int $id): bool;

    // ✅ add this to the code on th server And the searchByTerm method
    public function searchByTerm(string $term);
}
