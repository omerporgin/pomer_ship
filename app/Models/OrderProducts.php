<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    use HasFactory;

    /**
     * Relationship
     */
    protected $fillable = [
        'unique_id',
        'type',
        'order_id',
        'name',
        'quantity',
        'declared_quantity',
        'unit_price',
        'total_price',
        'declared_price',
        'total_custom_value',
        'sku',
        'gtip_code',
        'package_id',
        'sort',
        'width',
        'height',
        'length',
        'weight',
        'desi'
    ];

    protected $appends = [
        'calculated_desi',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }

    /**
     * @return mixed|string
     */
    public function calculatedDesi(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->desi * $this->quantity,
        );
    }
}
