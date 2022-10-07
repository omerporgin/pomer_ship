<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPackages extends Model
{
    use HasFactory;

    /**
     * Relationship
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id', 'order_id');
    }

}
