<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationState extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'country_code',
        'fips_code',
        'iso2',
        'type',
        'latitude',
        'longitude',
        'flag',
        'wikiDataId',
        'is_accepted',
    ];

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
