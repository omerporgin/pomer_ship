<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatuses extends Model
{

    use HasFactory;

    public $fillable = [
        'name',
        'from',
        'to',
        'color',
        'status_of',
        'show_on_menu'
    ];

    public $timestamps = false;

    /**
     * Relationship
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'id', 'status');
    }
}
