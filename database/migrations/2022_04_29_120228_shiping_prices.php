<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {


    /**
     * @return string
     */
    public function tableName(): string
    {
        return App\Services\ShippingPricesService::tableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName(), function (Blueprint $table) {

            $table->increments('id');
            $table->unsignedInteger('shipping_id');
            $table->string('service', 100)->nullable();
            $table->unsignedDecimal('desi', 11, 2)->default(0)->nullable();
            $table->unsignedDecimal('price', 11, 2)->default(0)->nullable();
            $table->string('currency', 100)->nullable();
            $table->unsignedTinyInteger('region');
            $table->text('data')->comment('Service response')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Create indexes
            $indexNames = ['id', 'shipping_id', 'service', 'desi', 'price', 'currency', 'region'];
            foreach ($indexNames as $newIndex) {
                $table->index($newIndex);
                $table->renameIndex($this->tableName() . '_' . $newIndex . '_index', $newIndex);
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
