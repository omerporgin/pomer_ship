<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Notifications\VerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'users';

    protected $fillable = [
        'account_name',
        'full_name',
        'email',
        'api_pass',
        'password',
        'address',
        'user_type',
        'identity',
        'company_name',
        'company_owner',
        'company_tax',
        'company_taxid',

        'bank',
        'user_group_id',
        'permission_id',
        'lang',

        'is_same_address',

        'warehouse_address',
        'warehouse_postal_code',
        'warehouse_state_id',
        'warehouse_city_id',
        'warehouse_phone',
        'warehouse_closed_at',
        'warehouse_location',

        'invoice_address',
        'invoice_postal_code',
        'invoice_state_id',
        'invoice_city_id',
        'invoice_phone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'real_company_name',
        'real_owner_name',
        'real_tax_id',
        'city_name',
        'state_name',
        'avatar',
    ];

    /**
     * Kişisel üyelik ise kendi ismi, kurumsal üyelik ise company owner
     *
     * @return string
     */
    public function realOwnerName(): Attribute
    {
        $name = $this->company_owner;
        if ($this->user_type == 0) {
            $name = $this->full_name;
        }

        return Attribute::make(
            get: fn($value) => $name,
        );
    }

    /**
     * Kişisel üyelik ise kendi ismi, kurumsal üyelik ise company owner
     *
     * @return Attribute
     */
    public function realCompanyName(): Attribute
    {
        $name = $this->company_name;
        if ($this->user_type == 0) {
            $name = $this->full_name;
        }
        return Attribute::make(
            get: fn($value) => $name,
        );
    }

    /**
     * Kişisel üyelik ise kendi ismi, kurumsal üyelik ise company owner
     *
     * @return Attribute
     */
    public function realTaxId(): Attribute
    {
        $taxId = $this->company_taxid;
        if ($this->user_type == 0) {
            $taxId = $this->identity;
        }
        return Attribute::make(
            get: fn($value) => $taxId,
        );
    }

    /**
     * @return Attribute
     */
    public function cityName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => LocationCity::find($this->warehouse_city_id)?->name,
        );
    }

    /**
     * @return Attribute
     */
    protected function stateName(): Attribute
    {
        return Attribute::make(
            get: fn($value) => LocationState::find($this->warehouse_state_id)?->name,
        );
    }

    /**
     * @return Attribute
     */
    protected function avatar(): Attribute
    {
        $avatar = 'img/user/user.' . $this->id . '.webp';

        if (!file_exists(storage_path('app/public/' . $avatar))) {
            $avatar = 'img/undraw_profile.svg';
        } else {
            $avatar = 'storage/' . $avatar;
        }

        return Attribute::make(
            get: fn($value) => $avatar,
        );
    }

    /**
     * @return Attribute
     */
    protected function permissionList(): Attribute
    {
        $permissionList = $this->permission()->first()?->permission;
        $permissionList = json_decode($permissionList, JSON_OBJECT_AS_ARRAY);
        return Attribute::make(
            get: fn($value) => $permissionList,
        );
    }

    /**
     * Relationship
     */
    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }

    public function userCreate()
    {
        return $this->hasMany(PaymentAccount::class, 'user_id', 'id');
    }

    public function userGroup()
    {
        return $this->hasOne(UserGroup::class, 'id', 'user_group_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'id', 'vendor_id');
    }

    public function city()
    {
        return $this->belongsTo(LocationCity::class, 'id', 'city_id');
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail); // my notification
    }
}
