<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function tableName()
    {
        return \App\Services\GtipService::tableName();
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
            $table->string('gtip' , 100)->comment('Gtipcode')->nullable();
            $table->string('description' , 1500)->comment('Gtip description')->nullable();
            $table->unsignedTinyInteger('is_selectable')->default(0);
            $table->text('search')->nullable();
            $table->string('unit' , 100)->comment('Gtip unit')->nullable();
            $table->string('tax' , 100)->nullable();

            $table->engine = 'MyIsam';

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
