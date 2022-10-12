<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPackages extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'width',
        'height',
        'length',
        'weight',
        'desi',
        'calculated_desi',
        'region',
        'shipment_id',
        'tracking_number',
        'tracking_status'
    ];

    /**
     * Relationship
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }

}
