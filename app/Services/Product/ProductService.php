<?php

namespace App\Services\Product;

use LaravelEasyRepository\BaseService;

interface ProductService extends BaseService{

    // Write something awesome :)

     public function createProduct(array $data);
    public function getProductBySku(string $sku);
    public function getAllProducts();
    public function updateStock(int $productId, int $quantity);

    // 🔹 Add these missing ones
    public function updateProduct(int $id, array $data);
    public function deleteProduct(int $id);
}
