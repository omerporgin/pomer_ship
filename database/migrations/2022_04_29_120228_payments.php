<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function tableName()
    {
        return App\Services\PaymentService::tableName();
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
            $table->string('processor', 30)->nullable();
            $table->unsignedTinyInteger('active')->default(0);
            $table->char('name' , 100);
            $table->char('user' , 100)->nullable();
            $table->char('key' , 100)->nullable();
            $table->char('pass' , 100)->nullable();
            $table->char('success_url' , 255)->nullable();
            $table->char('fail_url' , 255)->nullable();
            $table->char('callback_url' , 255)->nullable();

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
