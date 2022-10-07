<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsTable extends Migration
{

    /**
     *
     */
    public function tableName()
    {
        return \App\Services\BlogService::tableName();
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
            $table->unsignedTinyInteger('active')->default(0);
            $table->unsignedSmallInteger('lang');

            $table->unsignedInteger('user_id')->nullable();
            $table->string('view', 100 )->nullable()->comment('format : blog_viewname')->default(NULL);
            $table->string('url', 100)->nullable();
            $table->string('headline', 150);
            $table->string('lead', 255)->nullable();
            $table->text('body')->nullable();
            $table->string('title', 150)->default('');
            $table->string('description', 255)->default('');
            $table->timestamps();

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Create indexes
            $indexNames = ['id', 'lang', 'active', 'user_id'];
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
