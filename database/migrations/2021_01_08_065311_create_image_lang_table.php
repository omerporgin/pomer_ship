<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageLangTable extends Migration
{

    public function tableName()
    {
        return \App\Services\ImageLangService::tableName();
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
            $table->unsignedSmallInteger('lang');
            $table->unsignedInteger('type_id');
            $table->string('alt', 125)->nullable();
            $table->string('title', 125)->nullable();
            $table->string('real_name', 100)->nullable();

            $table->engine = 'MyIsam';

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Create indexes
            $indexNames = ['id', 'type_id', 'lang'];
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
}
