<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    /**
     * @return string
     */
    protected function tableName()
    {
        return \App\Services\OrderService::tableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName(), function (Blueprint $table) {

            $table->increments('id')->from(140000);

            // Order Data
            $table->unsignedTinyInteger('status')->comment('Sipariş Durumu')->nullable();
            $table->unsignedTinyInteger('real_status')->comment('Orders Exporgin status. 13->initiated')
                ->default(13)->nullable();
            $table->unsignedInteger('vendor_id');
            $table->unsignedTinyInteger('entegration_id')->nullable()->comment('Entegrasyon ile açılmayan siparişler null');
            $table->string('order_id', 50)->nullable();

            // Price
            $table->unsignedTinyInteger('currency')->nullable();
            $table->unsignedDecimal('total_price', 11, 2)->default(0)->comment('2 digits, Toplam Fiyat')->nullable();
            $table->unsignedDecimal('declared_price', 11, 2)->default(0)->comment('2 digits, Beyan Edilen Fİyat')->nullable();
            $table->string('se_label', 50)->comment('SE Etiket No')->nullable();
            $table->string('invoice_no', 50)->comment('Fatura numarası')->nullable();

            // Personal data
            $table->string('full_name', 255)->comment('Alıcı Adı Soyadı')->nullable();
            $table->string('company_name', 255)->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('message', 500)->comment('Hediye mesajı')->nullable();
            $table->string('description', 500)->comment('Paket içeriği hakkında açıklama')->nullable();

            // Shipment address
            $table->string('address', 1000)->comment('Tüm yapılara uyması için Adres_1 adres_2 olarak değil, tek büyük adres satırı olarak açıldı.')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('state_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->char('post_code', 10)->comment('Zip Codes may have extensions like : 12345-6789')->nullable();

            // Pickup Address
            $table->unsignedInteger('has_pickup')->default(1);
            $table->unsignedInteger('has_diffrent_pickup_address')->default(0);

            // Must be same as user tale
            $table->string('pickup_closed_at', 5)->comment('Close time of pickup address')->nullable()->default('18:00');
            $table->string('pickup_location', 80)->comment('Where the package should be picked up by DHL courier')->nullable
            ()->default('Front door');

            $table->string('pickup_address', 1000)->comment('Tüm yapılara uyması için Adres_1 adres_2 olarak değil, tek büyük adres satırı olarak açıldı.')->nullable();
            $table->unsignedInteger('pickup_state_id')->nullable();
            $table->unsignedInteger('pickup_city_id')->nullable();
            $table->char('pickup_post_code', 10)->comment('Zip Codes may have extensions like : 12345-6789')->nullable();



            $table->longText('log')->nullable();
            $table->longText('data')->comment('First saved data')->nullable();

            // Dates
            $table->dateTime('order_date', $precision = 0)->comment(' Siparişin verildiği tarih');
            $table->date('shipped_at')->comment(' Siparişin gönderileceği tarih');
            $table->timestamps();

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';
/*
            // Create indexes
            $indexNames = ['id', 'state_id', 'country_id'];
            foreach ($indexNames as $newIndex) {
                $table->index($newIndex);
                $table->renameIndex($this->tableName() . '_' . $newIndex . '_index', $newIndex);
            }*/

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
