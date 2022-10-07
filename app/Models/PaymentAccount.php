<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentAccount extends Model
{
    use HasFactory;
    protected $table = "payment_accounts";

    public function paymentAcc()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

}
