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
        return \App\Services\OrderProductService::tableName();;
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
            $table->string('unique_id', 255)->comment('Entegrations unique id')->nullable();
            $table->unsignedTinyInteger('type')->default(0)->comment('0->product, 1->shipipng 2->payment');
            $table->unsignedInteger('order_id');
            // Order Data
            $table->string('name',255)->comment('Name in english')->nullable();
            $table->unsignedTinyInteger('quantity')->nullable();
            $table->unsignedTinyInteger('declared_quantity')->comment('Beyan Edilen Adet')->nullable();
            $table->unsignedDecimal('unit_price', 11, 2)->default(0)->comment('2 digits, Birim Fiyat')->nullable();
            $table->unsignedDecimal('total_price', 11, 2)->default(0)->comment('2 digits, Toplam Fiyat')->nullable();
            $table->unsignedDecimal('declared_price', 11, 2)->default(0)->comment('2 digits, Beyan Edilen Fİyat')->nullable();
            $table->unsignedDecimal('total_custom_value', 11, 2)->default(0)->comment('2 digits,Toplam Gümrük Değeri')->nullable();
            $table->string('sku', 255)->comment('Stock keeping unit')->nullable();
            $table->string('gtip_code', 16)->comment('gtip code 12 digits with 3 dots 123433.123.123.312')->nullable();
            $table->unsignedInteger('package_id')->nullable();
            $table->unsignedDecimal('width', 11, 2)->nullable()->comment('');
            $table->unsignedDecimal('height', 11, 2)->nullable()->comment('');
            $table->unsignedDecimal('length', 11, 2)->nullable()->comment('');
            $table->unsignedDecimal('desi', 11, 2)->nullable()->comment('Desi can be decimal');
            $table->unsignedTinyInteger('sort')->default(0)->nullable();

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
