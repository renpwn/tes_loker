<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'rate',
    ];

    public function categories()
    {
        return $this->belongsToMany(
            Category::class,
            'category_tax_rules'
        );
    }
}
