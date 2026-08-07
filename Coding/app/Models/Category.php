<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'is_service',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function taxRules()
    {
        return $this->belongsToMany(
            TaxRule::class,
            'category_tax_rules'
        );
    }
}
