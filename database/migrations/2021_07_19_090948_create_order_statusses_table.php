<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Bu sayfaya eklenen satırların status_of=0 olanlar entegrations tablosuna status_id olarak eklenmeli.
     */
    public static $orderStatus = [
        [1, 'Pending', '', '', '97BFB4', 0, 0],
        [2, 'Processed', '', '', '519259', 0, 0],
        [3, 'Shipped', '', '', '064635', 0, 0],
        [4, 'Completed', '', '', '8A8635', 0, 0],
        [5, 'Expired', '', '', 'AE431E', 0, 0],
        [6, 'Failed', '', '', 'CD1818', 0, 0],
        [7, 'Voided', '', '', '0F2C67', 0, 0],
        [8, 'Cancelled', '', '', '2E4C6D', 0, 0],
        [9, 'Returned', '', '', '396EB0', 0, 0],
        [10, 'Returning', '', '', '009DAE', 0, 0],
        [11, 'Refunded', '', '', '678983', 0, 0],
        [12, 'Undefined', '', '', 'FF0000', 0, 0],

        // Status Of shipExporgin
        [13, 'Initiated', '', '', '009624', 1, 1],
        [14, 'Labelled', '13', '1,16,21', '247881', 1, 1],
        [15, 'Shipped to Exporgin', '14', '16', '00b8d4', 1, 0],
        [16, 'On Exporgin', '13,15', '17', '2979ff', 1, 1],
        [17, 'Shipped', '16', '18,19,21', '651fff', 1, 1],
        [18, 'Completed', '17', '', '8e24aa', 1, 1],
        [19, 'Returning', '17', '20', 'f50057', 1, 0],
        [20, 'Returned', '19', '23', 'd50000', 1, 0],
        [21, 'Not Accepted', '14', '6', '330000', 1, 0],
        [22, 'Terminated', '17', '', '000000', 1, 0],

        [23, 'Returning to Vendor', '20', '24', '2E0249', 1, 0],
        [24, 'Returned to vendor', '23', '', '570A57', 1, 0],
        [25, 'Wrong data', '', '', 'f48fb1', 1, 1],
    ];

    /**
     * The database schema.
     *
     * @var \Illuminate\Database\Schema\Builder
     */
    protected $schema;

    /**
     * Create a new migration instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->schema = Schema::connection($this->getConnection());
    }

    public function tableName()
    {
        return \App\Services\OrderStatusService::tableName();
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
            $table->string('name', 50);
            $table->string('from', 20);
            $table->string('to', 10);
            $table->char('color', 6);
            $table->unsignedTinyInteger('status_of')->default(0)->comment('0->Vendor, 1->ShipExporgin');
            $table->unsignedTinyInteger('show_on_menu')->default(0);

            $table->engine = 'MyISAM';

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Create indexes
            $indexNames = ['id'];
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

    /**
     * @return void
     */
    public function insert()
    {
        foreach (self::$orderStatus as $item) {
            DB::insert("INSERT INTO `" . $this->tableName() . "` (`id`, `name`, `from`, `to`, `color`, `status_of`, `show_on_menu`) VALUES (" .
                $item[0] . ", '" . $item[1] . "', '" . $item[2] . "', '" . $item[3] . "', '" . $item[4] . "'," .
                $item[5] . ", ".$item[6].")
            ;");
        }
    }
};
