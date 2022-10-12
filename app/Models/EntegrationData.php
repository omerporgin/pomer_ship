<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntegrationData extends Model
{

    use HasFactory;

    protected $fillable = ['entegration_id', 'user_id', 'url', 'user', 'pass', 'last_date', 'days', 'cargo_id', 'statuses', 'max'];

    protected $appends = [
        'cargo_list',
    ];

    /**
     * Kişisel üyelik ise kendi ismi, kurumsal üyelik ise company owner
     *
     * @return string
     */
    public function cargoList(): Attribute
    {
        $list = explode(',', $this->cargo_id);

        return Attribute::make(
            get: fn($value) => $list,
        );
    }
}
