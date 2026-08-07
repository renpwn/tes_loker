<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partner extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'npwp',
        'address',
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
