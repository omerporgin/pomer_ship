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
        return \App\Services\OrderPackageService::tableName();
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
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedDecimal('width', 11, 2)->default(0)->nullable();
            $table->unsignedDecimal('height', 11, 2)->default(0)->nullable();
            $table->unsignedDecimal('length', 11, 2)->default(0)->nullable();
            $table->unsignedDecimal('weight', 11, 2)->default(0)->nullable();
            $table->unsignedDecimal('desi', 11, 2)->nullable()->comment('Desi can be decimal');
            $table->unsignedDecimal('calculated_desi', 11, 2)->nullable()->comment('Desi can be decimal');
            $table->unsignedInteger('region')->nullable();

            $table->unsignedInteger('shipment_id')->nullable();
            $table->string('tracking_number', 50)->comment('Takip No')->nullable();
            $table->unsignedTinyInteger('tracking_status')->comment('Takip Durumu')->nullable();

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
