<?php

namespace App\Services\Product;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\Product\ProductRepository;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProductServiceImplement extends ServiceApi implements ProductService{

    /**
     * set title message api for CRUD
     * @param string $title
     */
     protected string $title = "";
     /**
     * uncomment this to override the default message
     * protected string $create_message = "";
     * protected string $update_message = "";
     * protected string $delete_message = "";
     */

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
      $this->productRepository = $productRepository;
    }

    // Define your custom methods :)

   public function createProduct(array $data)
        {
            try {

                // ✅ If category_id is missing or invalid, auto-create
                if (!empty($data['category_id'])) {
                    $category = Category::find($data['category_id']);
                    if (!$category) {
                        // Create a new category dynamically
                        $category = Category::create([
                            'id' => $data['category_id'], // optional, let DB auto-increment if you don’t want manual id
                            'name' => "Category " . $data['category_id'],
                        ]);
                    }
                } else {
                    // fallback if no category_id is provided
                    $category = Category::firstOrCreate(['name' => 'Uncategorized']);
                }

                 $data['category_id'] = $category->id;

                // Handle image upload if exists
                if(isset($data['image'])){
                    $imagePath = $data['image']->store('products', 'public');
                    $data['image_url'] = url("storage/$imagePath");
                    unset($data['image']);
                }

                $product = $this->productRepository->createProduct($data);

                return $this->setCode(201)
                            ->setMessage("Product created successfully")
                            ->setData(new ProductResource($product));

            } catch (\Exception $e) {
                return $this->setCode(400)
                            ->setMessage("Failed to create product")
                            ->setError($e->getMessage());
            }
        }

    // ✅ Get product by SKU
    public function getProductBySku(string $sku)
    {
        $product = $this->productRepository->findProductBySku($sku);
        if (!$product) {
            return $this->setCode(404)->setMessage("Product not found");
        }
        return $this->setCode(200)->setMessage("Product found")->setData(new ProductResource($product));
    }

      // ✅ Get all products
    public function getAllProducts()
    {
        $products = $this->productRepository->getAll();
        return $this->setCode(200)
                    ->setMessage("Products retrieved")
                    ->setData(ProductResource::collection($products));
    }

    // ✅ Update stock
    public function updateStock(int $productId, int $quantity)
    {
        try {
            $product = $this->productRepository->updateStock($productId, $quantity);
            return $this->setCode(200)
                        ->setMessage("Stock updated successfully")
                        ->setData(new ProductResource($product));
        } catch (\Exception $e) {
            return $this->setCode(400)
                        ->setMessage("Failed to update stock")
                        ->setError($e->getMessage());
        }
    }


  public function updateProduct(int $id, array $data) : ProductService
    {
        try {

            Log::info("Incoming update data for product {$id}", $data);
            if (isset($data['image'])) {
                $imagePath = $data['image']->store('products', 'public');
                $data['image_url'] = url("storage/$imagePath");
                unset($data['image']);
            }

            Log::info("Final payload to repository", $data);
            $product = $this->productRepository->updateProduct($id, $data);

            if (!$product) {
                return $this->setCode(404)
                            ->setMessage("Product not found or update failed")
                            ->setError("Update failed for product ID {$id}");
            }

            return $this->setCode(200)
                        ->setMessage("Product updated successfully")
                        ->setData(new ProductResource($product));
        } catch (\Exception $e) {
            return $this->setCode(400)
                        ->setMessage("Failed to update product")
                        ->setError($e->getMessage());
        }
    }




    public function deleteProduct(int $id)
    {
        try {
            $this->productRepository->delete($id);

            return $this->setCode(200)
                        ->setMessage("Product deleted successfully");
        } catch (\Exception $e) {
            return $this->setCode(400)
                        ->setMessage("Failed to delete product")
                        ->setError($e->getMessage());
        }
    }


    public function searchProducts(string $term)
{
    $products = $this->productRepository->searchByTerm($term);

    return $this->setCode(200)
                ->setMessage("Search results")
                ->setData(ProductResource::collection($products));
}




}
