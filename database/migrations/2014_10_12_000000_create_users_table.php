<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id()->from(140000);
            $table->string('full_name', 255);
            $table->string('company_name', 255)->nullable();
            $table->string('account_name', 255)->nullable();

            $table->unsignedTinyInteger('user_type')->default(0)->comment('0->Kişisel , 1->Kurumsal');
            $table->string('identity', 30)->nullable();
            $table->string('company_owner', 100)->nullable();
            $table->string('company_name', 100)->nullable();
            $table->string('company_tax', 100)->nullable();
            $table->string('company_taxid', 100)->nullable();
            $table->string('bank', 100)->nullable();
            $table->string('email', 100)->unique();
            $table->string('api_pass', 255)->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->string('profile_photo_path', 2048)->nullable();

            // Custom fields
            $table->unsignedTinyInteger('permission_id')->default(1)->comment("1-unverified, 2-standart user, 3 or more has admin permissions");
            $table->unsignedTinyInteger('user_group_id')->default(1)->nullable();
            $table->unsignedTinyInteger('lang')->default(29);

            $table->unsignedTinyInteger('is_same_address')->default(0)->comment('If invoice and warehouse addresses are the same');

            $table->string('warehouse_address', 255)->nullable();
            $table->string('warehouse_postal_code', 10)->nullable();
            $table->unsignedInteger('warehouse_state_id')->nullable();
            $table->unsignedInteger('warehouse_city_id')->nullable();
            $table->string('warehouse_phone', 50)->nullable();

            // Must be same as user tale
            $table->string('warehouse_closed_at', 5)->comment('Close time of pickup address')->nullable()->default('18:00');
            $table->string('warehouse_location', 80)->comment('Where the package should be picked up by DHL courier')->nullable
            ()->default('Front door');

            $table->string('invoice_address', 255)->nullable();
            $table->string('invoice_postal_code', 10)->nullable();
            $table->unsignedInteger('invoice_state_id')->nullable();
            $table->unsignedInteger('invoice_city_id')->nullable();
            $table->string('invoice_phone', 50)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
