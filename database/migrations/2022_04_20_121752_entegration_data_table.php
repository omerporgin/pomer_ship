<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function tableName()
    {
        return \App\Services\EntegrationDataService::tableName();
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
            $table->unsignedInteger('entegration_id');
            $table->unsignedInteger('user_id')->comment('shipExporgin user.');
            $table->string('url', 255)->nullable();
            $table->string('user', 100)->comment('Api user')->nullable();
            $table->string('pass', 100)->comment('Api pass')->nullable();
            $table->dateTime('last_date')->comment('En son indirilen tarih.')->nullable();
            $table->unsignedTinyInteger('days')->comment('Son tarihten sonra kaç gün indirilecek.')->default(1);
            $table->string('statuses', 20)->comment('Statusses of orders.')->nullable();
            $table->unsignedTinyInteger('max')->comment('Indirilecek max sipariş')->default(100);



            // Dates
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
