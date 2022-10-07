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
        return App\Services\ShippingService::tableName();
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
            $table->string('name', 100);
            $table->string('processor', 100)->nullable()->comment('ups, dhl');

            $table->string('account_number', 100)->nullable();
            $table->string('user', 100)->nullable();
            $table->string('api_key', 100)->nullable();
            $table->string('api_secret', 100)->nullable();

            $table->string('test_account_number', 100)->nullable();
            $table->string('test_user', 100)->nullable();
            $table->string('test_api_key', 100)->nullable();
            $table->string('test_api_secret', 100)->nullable();

            $table->unsignedTinyInteger('is_active')->default(1)->comment("1 active, 0 passive");
            $table->unsignedTinyInteger('is_test')->default(1)->comment("1 active, 0 passive");

            $table->string('zone_field', 100)->comment('This shipping will use this zone field in country table.')
                ->nullable();
            $table->unsignedTinyInteger('sort')->default(0);
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
