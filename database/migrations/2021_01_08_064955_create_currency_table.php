<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public static $currency = [
        ['Albania', 'Leke', 'ALL', 'Lek'],
        ['America', 'Dollars', 'USD', '$'],
        ['Afghanistan', 'Afghanis', 'AFN', '؋'],
        ['Argentina', 'Pesos', 'ARS', '$'],
        ['Aruba', 'Guilders', 'AWG', 'ƒ'],
        ['Australia', 'Dollars', 'AUD', '$'],
        ['Azerbaijan', 'New Manats', 'AZN', 'ман'],
        ['Bahamas', 'Dollars', 'BSD', '$'],
        ['Barbados', 'Dollars', 'BBD', '$'],
        ['Belarus', 'Rubles', 'BYR', 'p.'],
        ['Belgium', 'Euro', 'EUR', '€'],
        ['Beliz', 'Dollars', 'BZD', 'BZ$'],
        ['Bermuda', 'Dollars', 'BMD', '$'],
        ['Bolivia', 'Bolivianos', 'BOB', '\$b'],
        ['Bosnia and Herzegovina', 'Convertible Marka', 'BAM', 'KM'],
        ['Botswana', 'Pula', 'BWP', 'P'],
        ['Bulgaria', 'Leva', 'BGN', 'лв'],
        ['Brazil', 'Reais', 'BRL', 'R$'],
        ['Britain [United Kingdom]', 'Pounds', 'GBP', '£'],
        ['Brunei Darussalam', 'Dollars', 'BND', '$'],
        ['Cambodia', 'Riels', 'KHR', '៛'],
        ['Canada', 'Dollars', 'CAD', '$'],
        ['Cayman Islands', 'Dollars', 'KYD', '$'],
        ['Chile', 'Pesos', 'CLP', '$'],
        ['China', 'Yuan Renminbi', 'CNY', '¥'],
        ['Colombia', 'Pesos', 'COP', '$'],
        ['Costa Rica', 'Colón', 'CRC', '₡'],
        ['Croatia', 'Kuna', 'HRK', 'kn'],
        ['Cuba', 'Pesos', 'CUP', '₱'],
        ['Cyprus', 'Euro', 'EUR', '€'],
        ['Czech Republic', 'Koruny', 'CZK', 'Kč'],
        ['Denmark', 'Kroner', 'DKK', 'kr'],
        ['Dominican Republic', 'Pesos', 'DOP ', 'RD$'],
        ['East Caribbean', 'Dollars', 'XCD', '$'],
        ['Egypt', 'Pounds', 'EGP', '£'],
        ['El Salvador', 'Colones', 'SVC', '$'],
        ['England [United Kingdom]', 'Pounds', 'GBP', '£'],
        ['Euro', 'Euro', 'EUR', '€'],
        ['Falkland Islands', 'Pounds', 'FKP', '£'],
        ['Fiji', 'Dollars', 'FJD', '$'],
        ['France', 'Euro', 'EUR', '€'],
        ['Ghana', 'Cedis', 'GHC', '¢'],
        ['Gibraltar', 'Pounds', 'GIP', '£'],
        ['Greece', 'Euro', 'EUR', '€'],
        ['Guatemala', 'Quetzales', 'GTQ', 'Q'],
        ['Guernsey', 'Pounds', 'GGP', '£'],
        ['Guyana', 'Dollars', 'GYD', '$'],
        ['Holland [Netherlands]', 'Euro', 'EUR', '€'],
        ['Honduras', 'Lempiras', 'HNL', 'L'],
        ['Hong Kong', 'Dollars', 'HKD', '$'],
        ['Hungary', 'Forint', 'HUF', 'Ft'],
        ['Iceland', 'Kronur', 'ISK', 'kr'],
        ['India', 'Rupees', 'INR', 'Rp'],
        ['Indonesia', 'Rupiahs', 'IDR', 'Rp'],
        ['Iran', 'Rials', 'IRR', '﷼'],
        ['Ireland', 'Euro', 'EUR', '€'],
        ['Isle of Man', 'Pounds', 'IMP', '£'],
        ['Israel', 'New Shekels', 'ILS', '₪'],
        ['Italy', 'Euro', 'EUR', '€'],
        ['Jamaica', 'Dollars', 'JMD', 'J$'],
        ['Japan', 'Yen', 'JPY', '¥'],
        ['Jersey', 'Pounds', 'JEP', '£'],
        ['Kazakhstan', 'Tenge', 'KZT', 'лв'],
        ['Korea [North]', 'Won', 'KPW', '₩'],
        ['Korea [South]', 'Won', 'KRW', '₩'],
        ['Kyrgyzstan', 'Soms', 'KGS', 'лв'],
        ['Laos', 'Kips', 'LAK', '₭'],
        ['Latvia', 'Lati', 'LVL', 'Ls'],
        ['Lebanon', 'Pounds', 'LBP', '£'],
        ['Liberia', 'Dollars', 'LRD', '$'],
        ['Liechtenstein', 'Switzerland Francs', 'CHF', 'CHF'],
        ['Lithuania', 'Litai', 'LTL', 'Lt'],
        ['Luxembourg', 'Euro', 'EUR', '€'],
        ['Macedonia', 'Denars', 'MKD', 'ден'],
        ['Malaysia', 'Ringgits', 'MYR', 'RM'],
        ['Malta', 'Euro', 'EUR', '€'],
        ['Mauritius', 'Rupees', 'MUR', '₨'],
        ['Mexico', 'Pesos', 'MXN', '$'],
        ['Mongolia', 'Tugriks', 'MNT', '₮'],
        ['Mozambique', 'Meticais', 'MZN', 'MT'],
        ['Namibia', 'Dollars', 'NAD', '$'],
        ['Nepal', 'Rupees', 'NPR', '₨'],
        ['Netherlands Antilles', 'Guilders', 'ANG', 'ƒ'],
        ['Netherlands', 'Euro', 'EUR', '€'],
        ['New Zealand', 'Dollars', 'NZD', '$'],
        ['Nicaragua', 'Cordobas', 'NIO', 'C$'],
        ['Nigeria', 'Nairas', 'NGN', '₦'],
        ['North Korea', 'Won', 'KPW', '₩'],
        ['Norway', 'Krone', 'NOK', 'kr'],
        ['Oman', 'Rials', 'OMR', '﷼'],
        ['Pakistan', 'Rupees', 'PKR', '₨'],
        ['Panama', 'Balboa', 'PAB', 'B/.'],
        ['Paraguay', 'Guarani', 'PYG', 'Gs'],
        ['Peru', 'Nuevos Soles', 'PEN', 'S/.'],
        ['Philippines', 'Pesos', 'PHP', 'Php'],
        ['Poland', 'Zlotych', 'PLN', 'zł'],
        ['Qatar', 'Rials', 'QAR', '﷼'],
        ['Romania', 'New Lei', 'RON', 'lei'],
        ['Russia', 'Rubles', 'RUB', 'руб'],
        ['Saint Helena', 'Pounds', 'SHP', '£'],
        ['Saudi Arabia', 'Riyals', 'SAR', '﷼'],
        ['Serbia', 'Dinars', 'RSD', 'Дин.'],
        ['Seychelles', 'Rupees', 'SCR', '₨'],
        ['Singapore', 'Dollars', 'SGD', '$'],
        ['Slovenia', 'Euro', 'EUR', '€'],
        ['Solomon Islands', 'Dollars', 'SBD', '$'],
        ['Somalia', 'Shillings', 'SOS', 'S'],
        ['South Africa', 'Rand', 'ZAR', 'R'],
        ['South Korea', 'Won', 'KRW', '₩'],
        ['Spain', 'Euro', 'EUR', '€'],
        ['Sri Lanka', 'Rupees', 'LKR', '₨'],
        ['Sweden', 'Kronor', 'SEK', 'kr'],
        ['Switzerland', 'Francs', 'CHF', 'CHF'],
        ['Suriname', 'Dollars', 'SRD', '$'],
        ['Syria', 'Pounds', 'SYP', '£'],
        ['Taiwan', 'New Dollars', 'TWD', 'NT$'],
        ['Thailand', 'Baht', 'THB', '฿'],
        ['Trinidad and Tobago', 'Dollars', 'TTD', 'TT$'],
        ['Turkey', 'Lira', 'TRY', 'TL'],
        ['Turkey', 'Liras', 'TRL', '£'],
        ['Tuvalu', 'Dollars', 'TVD', '$'],
        ['Ukraine', 'Hryvnia', 'UAH', '₴'],
        ['United Kingdom', 'Pounds', 'GBP', '£'],
        ['United States of America', 'Dollars', 'USD', '$'],
        ['Uruguay', 'Pesos', 'UYU', '\$U'],
        ['Uzbekistan', 'Sums', 'UZS', 'лв'],
        ['Vatican City', 'Euro', 'EUR', '€'],
        ['Venezuela', 'Bolivares Fuertes', 'VEF', 'Bs'],
        ['Vietnam', 'Dong', 'VND', '₫'],
        ['Yemen', 'Rials', 'YER', '﷼'],
        ['Zimbabwe', 'Zimbabwe Dollars', 'ZWD', 'Z$'],
        ['India', 'Rupees', 'INR', '₹']
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
        return \App\Services\CurrencyService::tableName();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tableName(), function (Blueprint $table) {

            $table->tinyIncrements('id'); // 132 rows max, 255
            $table->string('country', 30);
            $table->string('currency', 20)->cımm;
            $table->string('code', 3)->comment('ISO 4217');
            $table->unsignedDecimal('value', 11, 2)->default(1);
            $table->string('symbol', 5)->nullable();
            $table->unsignedTinyInteger('active')->default(0);

            $table->timestamps();

            $table->engine = 'MyISAM';

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Create indexes
            $indexNames = ['id', 'active'];
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
        foreach (self::$currency as $item) {
            DB::insert("INSERT INTO `" . $this->tableName() . "` (`country`, `currency`, `code`, `symbol`) VALUES ('" .
                $item[0] . "', '" . $item[1] . "', '" . $item[2] . "', '" . $item[3] . "') ;");
        }
    }
};
