<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    use HasFactory;

    protected $fillable = [
        'processor',
        'active',
        'name',
        'user',
        'key',
        'pass',
        'success_url',
        'fail_url',
        'callback_url'
    ];

    public $timestamps = false;
}
