<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gtip extends Model
{
    use HasFactory;

    protected $table = 'gtips';
    protected $fillable=['gtip','description','is_selectable','search','unit','tax'];


    public $timestamps = FALSE;
}
