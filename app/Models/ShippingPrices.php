<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingPrices extends Model
{
    use HasFactory;

    protected $fillable = [
        'shipping_id',
        'service',
        'desi',
        'price',
        'currency',
        'region',
        'data',
    ];

}
