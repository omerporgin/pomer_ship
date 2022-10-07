<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function tableName()
    {
        return \App\Services\LocationCityService::tableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName(), function (Blueprint $table) {

            $table->mediumIncrements('id'); // max 65535
            $table->string('name', 255);
            $table->mediumInteger('state_id');
            $table->string('state_code', 100)->nullable();
            $table->mediumInteger('country_id');
            $table->string('country_code', 2)->comment('iso2');
            $table->decimal('latitude',11,8)->nullable();
            $table->decimal('longitude',11,8)->nullable();
            $table->tinyInteger('flag')->default(1)->nullable();
            $table->string('wikiDataId', 255)->nullable()->comment('Rapid API GeoDB Cities');
            $table->unsignedTinyInteger('is_accepted')->default(1)->comment('0 = New, 1 = Controlled and approved by admin');
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
        Schema::drop($this->tableName());
    }
};
