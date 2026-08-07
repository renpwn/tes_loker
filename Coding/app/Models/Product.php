<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'sku',
        'name',
        'price',
        'initial_stock',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    /* ========= BUSINESS LOGIC ========= */

    public function currentStock(): int
    {
        $in = $this->transactionItems()
            ->whereHas('transaction', fn ($q) =>
                $q->where('type', 'purchase')
            )
            ->sum('qty');

        $out = $this->transactionItems()
            ->whereHas('transaction', fn ($q) =>
                $q->where('type', 'sale')
            )
            ->sum('qty');

        return $this->initial_stock + $in - $out;
    }

    public function getCurrentStockAttribute(): int
    {
        $in = $this->transactionItems()
            ->whereHas('transaction', fn ($q) =>
                $q->where('type', 'purchase')
            )
            ->sum('qty');

        $out = $this->transactionItems()
            ->whereHas('transaction', fn ($q) =>
                $q->where('type', 'sale')
            )
            ->sum('qty');

        return $this->initial_stock + $in - $out;
    }
}
