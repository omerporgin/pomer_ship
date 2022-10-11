<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{

    use HasFactory;

    public $timestamps = false;

    protected $appends = [
        'account_number',
        'user',
        'api_secret',
        'api_key',
    ];

    /**
     * Mutator
     *
     * @return string
     */
    public function accountNumber(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($this->is_test) {
                    return $this->test_account_number;
                }
                return $value;
            },
            set: fn($value) => strtolower($value),
        );
    }

    /**
     * Mutator
     *
     * @return string
     */
    public function user(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($this->is_test) {
                    return $this->test_user;
                }
                return $value;
            },
            set: fn($value) => strtolower($value),
        );
    }

    /**
     * Mutator
     *
     * @return string
     */
    public function apiKey(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($this->is_test) {
                    return $this->test_api_key;
                }
                return $value;
            },
            set: fn($value) => $value,
        );
    }

    /**
     * Mutator
     *
     * @return string
     */
    public function apiSecret(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($this->is_test) {
                    return $this->test_api_secret;
                }
                return $value;
            },
            set: fn($value) => $value,
        );
    }
}
