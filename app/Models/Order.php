<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order_table';

    protected $appends = [
        'status',
        'real_status',
        'vendor_id',
        'entegration_id',
        'order_id',
        'currency',
        'total_price',
        'declared_price',
        'se_label',
        'invoice_no',
        'full_name',
        'company_name',
        'phone',
        'email',
        'message',
        'description',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'post_code',
        'has_pickup',
        'has_diffrent_pickup_address',
        'pickup_closed_at',
        'pickup_location',
        'pickup_address',
        'pickup_state_id',
        'pickup_city_id',
        'pickup_post_code',
        'log',
        'data',
        'order_date',
        'shipped_at'

    ];

    /**
     * @return void
     */
    public function currencyCode(): Attribute
    {
        $code = null;
        $currency = Currency::find($this->currency);
        if (!is_null($currency)) {
            $code = $currency->code;
        }
        return Attribute::make(
            get: fn($value) => $code,
        );
    }

    /**
     * @return void
     */
    public function documentCustom(): Attribute
    {
        $file = storage_path('app/public/documents/custom_' . $this->id . '.pdf');
        if (!file_exists($file)) {
            $url = '';
        } else {
            $url = url('/storage/documents/custom_' . $this->id . '.pdf');
        }
        return Attribute::make(
            get: fn($value) => $url,
        );
    }

    /**
     * @return void
     */
    public function documentInvoice(): Attribute
    {
        $file = storage_path('app/public/documents/commerical_invoice_' . $this->id . '.pdf');
        if (!file_exists($file)) {
            $url = '';
        } else {
            $url = url('/storage/documents/commerical_invoice_' . $this->id . '.pdf');
        }
        return Attribute::make(
            get: fn($value) => $url,
        );
    }

    /**
     * @return void
     */
    public function documentEtgb(): Attribute
    {
        $file = storage_path('app/public/documents/etgb_' . $this->id . '.pdf');
        if (!file_exists($file)) {
            $url = '';
        } else {
            $url = url('/storage/documents/etgb_' . $this->id . '.pdf');
        }
        return Attribute::make(
            get: fn($value) => $url,
        );
    }

    /**
     * @return mixed|string
     */
    public function cityName(): Attribute
    {
        $name = '';
        $service = service('LocationCity', $this->city_id);
        if ($service->hasItem()) {
            $name = $service->name;
        }
        return Attribute::make(
            get: fn($value) => $name,
        );
    }

    /**
     * @return mixed|string
     */
    public function stateName(): Attribute
    {
        $name = '';
        $service = service('LocationState', $this->state_id);
        if ($service->hasItem()) {
            $name = $service->name;
        }
        return Attribute::make(
            get: fn($value) => $name,
        );
    }

    /**
     * @return mixed|string
     */
    public function countryName(): Attribute
    {
        $name = '';
        $service = service('LocationCountry', $this->country_id);
        if ($service->hasItem()) {
            $name = $service->name;
        }
        return Attribute::make(
            get: fn($value) => $name,
        );
    }

    /**
     * @return string
     */
    public function pickupStateName(): Attribute
    {
        $name = '';
        $service = service('LocationState', $this->pickup_state_id);
        if ($service->hasItem()) {
            $name = $service->name;
        }

        return Attribute::make(
            get: fn($value) => $name,
        );
    }

    /**
     * @return string
     */
    public function pickupCityName(): Attribute
    {
        $name = '';
        $service = service('LocationCity', $this->pickup_city_id);
        if ($service->hasItem()) {
            $name = $service->name;
        }

        return Attribute::make(
            get: fn($value) => $name,
        );
    }

    /**
     * @return mixed|string
     */
    public function declaredValue(): Attribute
    {
        $sum = number_format($this->orderProducts->sum('total_price'), 2);

        return Attribute::make(
            get: fn($value) => floatval($sum),
        );
    }

    /**
     * Relationship
     */
    public function orderPackages()
    {
        return $this->hasMany(OrderPackages::class, 'order_id', 'id');
    }

    /**
     * Relationship
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'vendor_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderProducts()
    {
        return $this->hasMany(OrderProducts::class, 'order_id', 'id');
    }

}
