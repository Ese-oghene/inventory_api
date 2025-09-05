<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return true;

         // Only allow admins to add products
        return $this->user() && $this->user()->isAdmin();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
       return [
            'category_id' => 'nullable|integer',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock_qty' => 'required|integer|min:0',
            'color' => 'nullable|string|max:50',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // ✅ file validation

        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Category is required',
            'sku.unique' => 'SKU must be unique',
            'price.required' => 'Price is required',
            'stock_qty.required' => 'Stock quantity is required',
        ];
    }
}
