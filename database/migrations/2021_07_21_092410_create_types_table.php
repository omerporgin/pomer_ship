<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTypesTable extends Migration
{

    public function tableName()
    {
        return \App\Services\TypeService::tableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName(), function (Blueprint $table) {

            $table->tinyIncrements('id');
            $table->string('service', 100)->nullable();

            // Img config data
            $table->enum('ext', ['jpg', 'webp', 'png', 'gif', 'tif', 'bmp', 'ico', 'psd'])->default('webp')->comment('image extension');
            $table->unsignedTinyInteger('compression')->default(90);
            $table->unsignedSmallInteger('t')->default(60)->comment('Tiny');
            $table->unsignedSmallInteger('s')->default(150)->comment('Small');
            $table->unsignedSmallInteger('m')->default(500)->comment('Middle');
            $table->unsignedSmallInteger('b')->default(900)->comment('Big');
            $table->unsignedSmallInteger('g')->default(1500)->comment('Giant');

            $table->engine = 'innoDB';

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
        Schema::dropIfExists($this->tableName());
    }
}
