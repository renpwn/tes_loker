<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // bisa pakai policy / role check nanti
    }

    public function rules(): array
    {
        // 1. Ambil instance Model Category dari route binding (jika ada, yaitu saat UPDATE).
        //    Jika ini adalah operasi CREATE, maka $category akan bernilai null.
        $category = $this->route('category');
        
        // Tentukan ID yang akan diabaikan; null jika ini adalah CREATE.
        $ignoreId = $category ? $category->id : null;

        return [
            'code' => 'required|unique:categories,code,' . $ignoreId,
            'name' => 'required|string|max:255',
            'is_service' => 'boolean',
        ];
    }
}
