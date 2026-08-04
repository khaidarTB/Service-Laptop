<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sparepart extends Model
{
    use HasFactory;

    protected $fillable = [
        'part_name',
        'description',
        'image',
        'stock',
        'min_stock',
        'cost_price',
        'selling_price',
    ];

    public function serviceDetails(): HasMany
    {
        return $this->hasMany(ServiceDetail::class);
    }
}
