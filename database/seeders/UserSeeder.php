<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\PaymentAccount;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory(20)->create()->each(
            function ($user){
                $payment=PaymentAccount::factory(5)->make([
                    'user_id'=>$user->id
                ]);
                $user->userCreate()->saveMany($payment);
            }
        );
    }
}
