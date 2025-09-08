<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cashier' => $this->cashier->name,
            'sale_date' => $this->sale_date,
            'total_amount' => $this->total_amount,
            'items' => SaleItemResource::collection($this->saleItems),
            'payment_method' => $this->payment_method
        ];
    }
}
