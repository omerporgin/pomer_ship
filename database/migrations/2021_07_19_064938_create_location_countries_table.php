<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function tableName()
    {
        return \App\Services\LocationCountryService::tableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName(), function (Blueprint $table) {

            $table->mediumIncrements('id'); // max 255, we have max 200+ country
            $table->string('name', 100);
            $table->string('iso3', 3);
            $table->string('numeric_code', 3);
            $table->string('iso2', 3)->comment('ISO 3166-1 alpha-2');
            $table->string('phonecode', 255);
            $table->string('capital', 255);
            $table->string('currency', 255);
            $table->string('currency_name', 255);
            $table->string('currency_symbol', 255);
            $table->string('tld', 255);
            $table->string('native', 255)->nullable();
            $table->string('region', 255) ;
            $table->string('subregion', 255);
            $table->text('timezones');
            $table->text('translations');
            $table->decimal('latitude',11,8);
            $table->decimal('longitude',11,8);
            $table->string('emoji', 191);
            $table->string('emojiU', 191);
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId', 255)->nullable()->comment('Rapid API GeoDB Cities');
            $table->unsignedTinyInteger('is_accepted')->default(1)->comment('0 = New, 1 = Controlled and approved by admin');
            $table->unsignedTinyInteger('cargo_dhl_id')->nullable()->comment('DHL bölge kodu');
            $table->unsignedTinyInteger('cargo_ups_id')->nullable()->comment('UPS bölge kodu');
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
