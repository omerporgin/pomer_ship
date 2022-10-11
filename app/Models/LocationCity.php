<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationCity extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'state_id',
        'state_code',
        'country_id',
        'country_code',
        'latitude',
        'longitude',
        'flag',
        'wikiDataId',
        's_accepted',
    ];

    protected $appends = [
        'full_name',
    ];

    public function fullName(): Attribute
    {
        $country = service('LocationCountry', $this->country_id);
        $state = service('LocationState', $this->state_id);
        $city = service('LocationCity', $this->city_id);

        return Attribute::make(
            get: fn($value) => $country?->name . " > " . $state?->name . " > " . $city?->name,
        );
    }

    public function fullNameAsHtml(): Attribute
    {
        $return = '';
        $fullName = explode(' > ', $this->full_name);
        if (count($fullName) == 3) {
            $return = '
                <div class="text-nowrap" style="font-size:0.8em">
                    ' . $fullName[0] . " > " . $fullName[1] . '
                </div>
                <div class="text-primary">
                    <b>
                        ' . $fullName[2] . '
                    </b>
                </div>';
        }

        return Attribute::make(
            get: fn($value) => $return,
        );

    }
}
