<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPackageInvoice extends Model
{
    use HasFactory;
    protected $table='order_package_invoices';
    protected $fillable=[
        'order_package_id',
        'status',
        'price',
        'shipping_service_price'
    ];
}
