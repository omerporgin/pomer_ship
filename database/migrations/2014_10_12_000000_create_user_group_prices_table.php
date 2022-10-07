<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function tableName()
    {
        return \App\Services\UserGroupPriceService::tableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('user_group');
            $table->unsignedSmallInteger('shipping_id');
            $table->unsignedTinyInteger('is_default')->default(0);
            $table->unsignedTinyInteger('min')->comment('Minimum desi');
            $table->unsignedTinyInteger('max')->comment('Maximum desi');
            $table->double('price', 10, 2)->default(0.00)->comment('Default price');
            $table->double('discount', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->tableName());
    }
};
