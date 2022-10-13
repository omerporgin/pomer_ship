<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * @return string
     */
    protected function tableName()
    {
        return \App\Services\OrderPackageInvoiceService::tableName();
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
            $table->unsignedInteger('order_package_id');
            $table->unsignedTinyInteger('status');
            $table->unsignedDecimal('price', 11, 2)->default(0)->nullable()->comment('Calculated via data provided by vendor');
            $table->unsignedDecimal('shipping_service_price', 11, 2)->default(0)->nullable()->comment('Shipping service declared price');

            $table->timestamps();

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Create indexes
            $indexNames = ['id'];
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
