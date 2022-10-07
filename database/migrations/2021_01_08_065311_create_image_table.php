<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImageTable extends Migration
{

    public function tableName()
    {
        return \App\Services\ImageService::tableName();
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
            $table->unsignedTinyInteger('type')->comment('DB:types');
            $table->unsignedInteger('type_id');
            $table->unsignedTinyInteger('active')->default(0);
            $table->unsignedTinyInteger('sort')->default(0);

            $table->timestamps();

            $table->engine = 'MyIsam';

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Create indexes
            $indexNames = ['id', 'type', 'type_id', 'sort'];
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
