<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class ProductUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
          // Only allow admins to add products
        return $this->user() && $this->user()->isAdmin();

        //return true; // For simplicity, allow all for now
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            // 'category_id' => 'sometimes|nullable|integer',
            'name' => 'sometimes|string|max:255',
            'sku' => 'sometimes|string|unique:products,sku,' . $this->route('id'),
            'description' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'stock_qty' => 'sometimes|integer|min:0',
            'color' => 'sometimes|string|max:50',
            'image' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
