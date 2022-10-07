<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroupPrice extends Model
{
    use HasFactory;

    public $timestamps = false;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class, 'id', 'user_group');
    }
}
