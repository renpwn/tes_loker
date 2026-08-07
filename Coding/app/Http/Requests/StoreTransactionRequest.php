<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'required|in:purchase,sale',
            'date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ];
    }

    /**
     * 🔥 CUSTOM VALIDATION (SALE CHECK STOCK)
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            // hanya cek jika SALE
            if ($this->input('type') !== 'sale') {
                return;
            }

            foreach ($this->input('items', []) as $index => $item) {
                $product = Product::find($item['product_id']);

                if (!$product) {
                    continue;
                }

                if ($product->currentStock() < $item['qty']) {
                    $validator->errors()->add(
                        "items.$index.qty",
                        "Stock {$product->name} tidak cukup (tersedia {$product->currentStock()})"
                    );
                }
            }
        });
    }
}
