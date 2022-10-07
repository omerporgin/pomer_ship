<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public static $permissions = [
        [1, 'Unverified Vendor', 1, []],
        [2, 'Standart Vendor', 1, []],
        [13, 'Super Admin', 1, [
            "content_see" => 1,
            "content_save" => 1,
            "order_see" => 1,
            "order_save" => 1,
            "comments_see" => 1,
            "comments_save" => 1,
            "product_see" => 1,
            "product_save" => 1,
            "setting_save" => 1,
            "setting_see" => 1,
            "user_see" => 1,
            "user_save" => 1,
            "package_see" => 1,
            "package_save" => 1,
        ]],
        [14, 'Admin', 1, [
            "content_see" => 1,
            "content_save" => 1,
            "order_see" => 1,
            "order_save" => 1,
            "comments_see" => 1,
            "comments_save" => 1,
            "product_see" => 1,
            "product_save" => 1,
            "setting_save" => 1,
            "setting_see" => 1,
            "user_see" => 1,
            "user_save" => 1,
            "package_see" => 1,
            "package_save" => 1,
        ]],
        [15, 'Seo', 1, [
            "content_see" => 1,
            "content_save" => 1,
            "order_see" => 1,
            "order_save" => 1,
            "comments_see" => 1,
            "comments_save" => 1,
            "product_see" => 1,
            "product_save" => 1,
            "setting_save" => 1,
            "setting_see" => 1,
            "user_see" => 1,
            "user_save" => 1,
            "package_see" => 1,
            "package_save" => 1,
        ]],
        [16, 'Vendor Super Admin', 1, [
            "content_see" => 1,
            "content_save" => 1,
            "order_see" => 1,
            "order_save" => 1,
            "comments_see" => 1,
            "comments_save" => 1,
            "product_see" => 1,
            "product_save" => 1,
            "setting_save" => 1,
            "setting_see" => 1,
            "user_see" => 1,
            "user_save" => 1,
            "package_see" => 1,
            "package_save" => 1,
        ]],
        [17, 'Vendor Admin', 1, [
            "content_see" => 1,
            "content_save" => 1,
            "order_see" => 1,
            "order_save" => 1,
            "comments_see" => 1,
            "comments_save" => 1,
            "product_see" => 1,
            "product_save" => 1,
            "setting_save" => 1,
            "setting_see" => 1,
            "user_see" => 1,
            "user_save" => 1,
            "package_see" => 1,
            "package_save" => 1,
        ]]
    ];

    public function tableName()
    {
        return \App\Services\PermissionService::tableName();
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
            $table->string('name', 50);
            $table->unsignedTinyInteger('static')->default(0);
            $table->json('permission', 255)->nullable();
            $table->timestamps();

            $table->engine = 'innoDB';

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Create indexes
            $indexNames = ['id', 'static'];
            foreach ($indexNames as $newIndex) {
                $table->index($newIndex);
                $table->renameIndex($this->tableName() . '_' . $newIndex . '_index', $newIndex);
            }
        });

        $this->insert();
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

    public function insert()
    {
        foreach (self::$permissions as $item) {
            DB::insert("INSERT INTO `" . $this->tableName() . "` (`id`, `name`, `static`, `permission`) VALUES (" . $item[0] . ", '" . $item[1] . "', " . $item[2] . ", '" . json_encode($item[3]) . "')
            ;");
        }
    }
};
