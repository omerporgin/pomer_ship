<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingService extends Model
{
    use HasFactory;
    protected $table='shipping_services';
    protected $fillable=[
        'shipping_id',
        'name',
        'is_active'
    ];
}
