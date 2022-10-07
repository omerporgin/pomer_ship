<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HepsiJetToken extends Model
{

    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shipment_hepsijet_token';

    protected $fillable = [];

}
