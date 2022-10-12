<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    protected $fillable = [
        'name',
        'static',
        'permisson'
    ];

    use HasFactory;

    /**
     * Relationship
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'permission_id');
    }
}
