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
    protected $table = 'order_products';

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
