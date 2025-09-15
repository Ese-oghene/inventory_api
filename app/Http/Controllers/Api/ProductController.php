<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Product\ProductService;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Http\Requests\ProductUpdateRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // ✅ Admin only: add product
    public function store(ProductRequest $request)
    {
        return $this->productService->createProduct($request->validated())->toJson();
    }

     // ✅ Search product by SKU
    public function show($sku)
    {
        return $this->productService->getProductBySku($sku)->toJson();
    }

    // ✅ List all products
    public function index()
    {
        return $this->productService->getAllProducts()->toJson();
    }

    // ✅ Update product
    // public function update(ProductUpdateRequest  $request, int $id)
    // {
    //     return $this->productService->updateProduct($id, $request->validated())->toJson();
    // }

    public function update(ProductUpdateRequest $request, int $id)
{
    // Log::info("Raw request all()", $request->all());
    // Log::info("Request input()", $request->input());
    $data = $request->validated();

    Log::info("Validated request data", $data);
    // merge the file back into the payload
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image');
    }

    return $this->productService->updateProduct($id, $data)->toJson();
}


    // ✅ Delete product
    public function destroy(int $id)
    {
        return $this->productService->deleteProduct($id);
    }


    public function updateStock(Request $request, int $id)
{
    // dd($request->input('stock_qty'));
    $request->validate([
        'stock_qty' => 'sometimes|integer|min:0',
    ]);

    // dd($request->input('stock_qty'));

    $quantity = $request->input('stock_qty');

    return $this->productService->updateStock($id, $quantity)->toJson();
}

// add this to the code on the server
public function search(string $term)
{
    return $this->productService->searchProducts($term)->toJson();
}

}
