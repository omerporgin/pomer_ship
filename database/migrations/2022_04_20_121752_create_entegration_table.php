<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{

    public function tableName()
    {
        return \App\Services\EntegrationService::tableName();
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
            $table->string('name',100)->comment('Ürün')->nullable();
            $table->char('status_1',1)->nullable();
            $table->char('status_2',1)->nullable();
            $table->char('status_3',1)->nullable();
            $table->char('status_4',1)->nullable();
            $table->char('status_5',1)->nullable();
            $table->char('status_6',1)->nullable();
            $table->char('status_7',1)->nullable();
            $table->char('status_8',1)->nullable();
            $table->char('status_9',1)->nullable();
            $table->char('status_10',1)->nullable();
            $table->char('status_11',1)->nullable();

            // 12 is reserved for undefined types

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

        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (1, 'OpenCart', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (2, 'CsCart', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (3, 'Etsy', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (4, 'AliExpress', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (5, 'Amazon', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (6, 'Ebay', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (7, 'Shopify', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (8, 'Wix', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (9, 'Woocommerce', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (10, 'Çiçek Sepeti', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (11, 'T-soft', NULL, NULL);");
        DB::insert("INSERT INTO `".$this->tableName()."` (`id`, `name`, `created_at`, `updated_at`) VALUES (12, 'Ticimax', NULL, NULL);");
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
