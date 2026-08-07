<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // bisa pakai policy / role check nanti
    }

    public function rules(): array
    {        
        // Ambil Product dari route binding (EDIT)
        $product = $this->route('product');
        $ignoreId = $product?->id;

        return [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|unique:products,sku,' . $ignoreId,
            'price' => 'required|numeric|min:1',
            'initial_stock' => 'required|integer|min:0',
        ];

        // Ambil Category dari input
        // $category = Category::find($this->input('category_id'));

        // return [
        //     'category_id' => ['required', 'exists:categories,id'],
        //     'sku' => ['required', 'string', 'max:100', 'unique:products,sku,' . $ignoreId],
        //     'name' => ['required', 'string', 'max:255'],

        //     // 🔥 RULE BERGANTUNG CATEGORY
        //     'price' => [
        //         'required',
        //         'numeric',
        //         'min:1',
        //         $category && $category->is_service
        //             ? 'min:1000' // contoh: jasa minimal 1000
        //             : 'min:1'
        //     ],

        //     // 🔥 STOCK HANYA UNTUK BARANG
        //     'initial_stock' => [
        //         $category && $category->is_service ? 'nullable' : 'required',
        //         'integer',
        //         'min:0'
        //     ],
        // ];
    }
}
