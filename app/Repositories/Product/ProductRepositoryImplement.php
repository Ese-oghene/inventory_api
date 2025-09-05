<?php

namespace App\Repositories\Product;

use LaravelEasyRepository\Implementations\Eloquent;
use App\Models\Product;
use Illuminate\Support\Facades\Log;

class ProductRepositoryImplement extends Eloquent implements ProductRepository{

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected Product $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    // Write something awesome :)

    public function createProduct(array $data)
    {
        return $this->model->create($data);
    }

    public function findProductBySku(string $sku)
    {
        return $this->model->where('sku', $sku)->first();
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function updateStock(int $productId, int $quantity)
    {
        $product = $this->model->findOrFail($productId);
        $product->stock_qty = $quantity;
        $product->save();
        return $product;
    }

// public function updateProduct(int $id, array $data): ?Product
// {

//     $product = $this->model->find($id);

//     if (!$product) {
//         return null;
//     }

//     log::info("Repository update data", $data);
//     $product->update($data);
//      Log::info("DB product after update", $product->toArray());
//     //Log::info("Changed attributes", $product->getChanges());
//     return $product->fresh();
// }


    /**
     * Update a product by ID.
     */
    public function updateProduct( $id,  $data)
    {
        $product = $this->model->findOrFail($id);
        $product->update($data);
        return $product;
    }

public function deleteProduct(int $id): bool
{
    $product = $this->model->find($id);

    if (!$product) {
        return false;
    }

    return $product->delete();
}


}
