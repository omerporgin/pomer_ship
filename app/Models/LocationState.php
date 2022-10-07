<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationState extends Model
{

    use HasFactory;

    /**
     * Relationship
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id', 'district_id');
    }

    /**
     * Relationship
     */
    public function invoice_order()
    {
        return $this->belongsTo(Order::class, 'id', 'invoice_district_id');
    }
}
