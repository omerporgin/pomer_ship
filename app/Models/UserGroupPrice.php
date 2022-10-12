<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroupPrice extends Model
{
    use HasFactory;

    protected $table = 'user_group_prices';
    protected $fillable = [
        'user_group',
        'shipping_id',
        'is_default',
        'min',
        'max',
        'price',
        'discount',
    ];
    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class, 'id', 'user_group');
    }
}
