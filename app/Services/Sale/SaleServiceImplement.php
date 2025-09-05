<?php

namespace App\Services\Sale;

use LaravelEasyRepository\ServiceApi;
use App\Repositories\Sale\SaleRepository;
use App\Http\Resources\SaleResource;
use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;
use App\Services\Product\ProductService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;



class SaleServiceImplement extends ServiceApi implements SaleService{

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
     protected SaleRepository $saleRepository;
      protected ProductService $productService;

    public function __construct(SaleRepository $saleRepository, ProductService $productService)
    {

      $this->saleRepository = $saleRepository;
      $this->productService = $productService;

    }

    // Define your custom methods :
    public function RecordSale(array $data)
{
    try {
        return DB::transaction(function () use ($data) {
            $sale = $this->saleRepository->create([
                'cashier_id' => auth()->id(),
                'sale_date' => now(),
                'total_amount' => 0,
            ]);

            $totalAmount = 0;

            foreach ($data['items'] as $item) {
                // ✅ Lookup product by SKU instead of id
                $product = \App\Models\Product::where('sku', $item['sku'])->lockForUpdate()->firstOrFail();

                if ($product->stock_qty < $item['quantity']) {
                    throw new \Exception("Not enough stock for product: {$product->name}");
                }

                $subtotal = $product->price * $item['quantity'];

                $this->saleRepository->addSaleItem($sale, [
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $product->price,
                    'subtotal'   => $subtotal,
                ]);

                // ✅ Deduct stock
                $product->decrement('stock_qty', $item['quantity']);

                $totalAmount += $subtotal;
            }

            // ✅ Update sale total
            $this->saleRepository->updateSaleTotal($sale, $totalAmount);

            return $this->setCode(201)
                        ->setMessage("Sale created successfully")
                        ->setData(new SaleResource($sale->load('saleItems.product')));
        });
    } catch (\Exception $e) {
        return $this->setCode(400)
                    ->setMessage("Failed to create sale")
                    ->setError($e->getMessage());
    }
}




    public function getAllSales(int $perPage = 5): AnonymousResourceCollection
    {
        return SaleResource::collection($this->saleRepository->getAll($perPage));
    }

    public function getReceiptByDate(string $date): AnonymousResourceCollection
    {
        $sales = $this->saleRepository->getByDate($date, 1000); // all for the day
        return SaleResource::collection($sales);
    }

   public function getSalesByDate(string $date, int $perPage = 5): AnonymousResourceCollection
{
    $sales = $this->saleRepository->getByDate($date, $perPage);
    return SaleResource::collection($sales);
}

  public function getSaleById(int $id)
    {
        $sale = $this->saleRepository->getSaleById($id);

        if (!$sale) {
            return $this->setCode(404)->setMessage("Sale not found");
        }

        return $this->setCode(200)
                    ->setMessage("Sale retrieved successfully")
                    ->setData(new SaleResource($sale));
    }

}




  /**
     * Record a sale with multiple products.
     */
    // public function recordSale(array $data): SaleResource
    // {
    //     return DB::transaction(function () use ($data) {
    //         $total = 0;
    //         $saleItems = [];

    //         foreach ($data['products'] as $item) {
    //             $product = $this->productService->getProductBySku($item['product_id'])->getData()->data;

    //             // Optional: validate stock
    //             if ($item['quantity'] > $product->stock) {
    //                 throw new \Exception("Insufficient stock for product {$product->name}");
    //             }

    //             $subtotal = $product->price * $item['quantity'];
    //             $total += $subtotal;

    //             $saleItems[] = [
    //                 'product_id' => $item['product_id'],
    //                 'quantity' => $item['quantity'],
    //                 'unit_price' => $product->price,
    //                 'subtotal' => $subtotal,
    //             ];

    //             // Deduct stock
    //             $this->productService->updateStock($item['product_id'], -$item['quantity']);
    //         }

    //         $sale = $this->saleRepository->create([
    //             'cashier_id' => auth()->id(),
    //             'sale_date' => now(),
    //             'total_amount' => $total,
    //         ]);

    //         $sale->saleItems()->createMany($saleItems);

    //         return new SaleResource($sale);
    //     });
    // }



    //   public function recordSale(array $data): AnonymousResourceCollection
    // {
    //     return DB::transaction(function () use ($data) {

    //         $total = 0;
    //         $saleItems = [];

    //         foreach ($data['products'] as $item) {
    //             $product = $this->productService->getProductBySku($item['product_id'])->getData()->data;
    //             $subtotal = $product->price * $item['quantity'];
    //             $total += $subtotal;

    //             $saleItems[] = [
    //                 'product_id' => $item['product_id'],
    //                 'quantity' => $item['quantity'],
    //                 'unit_price' => $product->price,
    //                 'subtotal' => $subtotal,
    //             ];

    //             // Deduct stock
    //             $this->productService->updateStock($item['product_id'], -$item['quantity']);
    //         }

    //         $sale = $this->saleRepository->create([
    //             'cashier_id' => auth()->id(),
    //             'sale_date' => now(),
    //             'total_amount' => $total,
    //         ]);

    //         foreach ($saleItems as $item) {
    //             $sale->saleItems()->create($item);
    //         }

    //         return new SaleResource($sale);
    //     });
    // }



