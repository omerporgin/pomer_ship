<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public static $languages = [
        [1, 'af', 'Afrikaans', 3, 0, 0, 0],
        [2, 'ar-ae', 'Arabic [U.A.E.]', 3, 0, 0, 1],
        [3, 'ar-bh', 'Arabic [Bahrain]', 3, 0, 0, 1],
        [4, 'ar-dz', 'Arabic [Algeria]', 3, 0, 0, 1],
        [5, 'ar-eg', 'Arabic [Egypt]', 3, 0, 0, 1],
        [6, 'ar-iq', 'Arabic [Iraq]', 3, 0, 0, 1],
        [7, 'ar-jo', 'Arabic [Jordan]', 3, 0, 0, 1],
        [8, 'ar-kw', 'Arabic [Kuwait]', 3, 0, 0, 1],
        [9, 'ar-lb', 'Arabic [Lebanon]', 3, 0, 0, 1],
        [10, 'ar-ly', 'Arabic [Libya]', 3, 0, 0, 1],
        [11, 'ar-ma', 'Arabic [Morocco]', 3, 0, 0, 1],
        [12, 'ar-om', 'Arabic [Oman]', 3, 0, 0, 1],
        [13, 'ar-qa', 'Arabic [Qatar]', 3, 0, 0, 1],
        [14, 'ar-sa', 'Arabic [Saudi Arabia]', 3, 0, 0, 1],
        [15, 'ar-sy', 'Arabic [Syria]', 3, 0, 0, 1],
        [16, 'ar-tn', 'Arabic [Tunisia]', 3, 0, 0, 1],
        [17, 'ar-ye', 'Arabic [Yemen]', 3, 0, 0, 1],
        [18, 'be', 'Belarusian', 3, 0, 0, 0],
        [19, 'bg', 'Bulgarian', 3, 0, 0, 0],
        [20, 'ca', 'Catalan', 3, 0, 0, 0],
        [21, 'cs', 'Czech', 3, 0, 0, 0],
        [22, 'da', 'Danish', 3, 0, 0, 0],
        [23, 'de', 'German [Standard]', 3, 0, 0, 0],
        [24, 'de-at', 'German [Austria]', 3, 0, 0, 0],
        [25, 'de-ch', 'German [Switzerland]', 3, 0, 0, 0],
        [26, 'de-li', 'German [Liechtenstein]', 3, 0, 0, 0],
        [27, 'de-lu', 'German [Luxembourg]', 3, 0, 0, 0],
        [28, 'el', 'Greek', 3, 0, 0, 0],
        [29, 'en', 'English', 1, 1, 2, 0],
        [30, 'en-au', 'English [Australia]', 3, 0, 0, 0],
        [31, 'en-bz', 'English [Belize]', 3, 0, 0, 0],
        [32, 'en-ca', 'English [Canada]', 3, 0, 0, 0],
        [33, 'en-gb', 'English [United Kingdom]', 3, 0, 0, 0],
        [34, 'en-ie', 'English [Ireland]', 3, 0, 0, 0],
        [35, 'en-jm', 'English [Jamaica]', 3, 0, 0, 0],
        [36, 'en-nz', 'English [New Zealand]', 3, 0, 0, 0],
        [37, 'en-tt', 'English [Trinidad]', 3, 0, 0, 0],
        [38, 'en-us', 'English [United States]', 3, 0, 0, 0],
        [39, 'en-za', 'English [South Africa]', 3, 0, 0, 0],
        [40, 'es', 'Spanish [Spain]', 3, 0, 0, 0],
        [41, 'es-ar', 'Spanish [Argentina]', 3, 0, 0, 0],
        [42, 'es-bo', 'Spanish [Bolivia]', 3, 0, 0, 0],
        [43, 'es-cl', 'Spanish [Chile]', 3, 0, 0, 0],
        [44, 'es-co', 'Spanish [Colombia]', 3, 0, 0, 0],
        [45, 'es-cr', 'Spanish [Costa Rica]', 3, 0, 0, 0],
        [46, 'es-do', 'Spanish [Dominican Republic]', 3, 0, 0, 0],
        [47, 'es-ec', 'Spanish [Ecuador]', 3, 0, 0, 0],
        [48, 'es-gt', 'Spanish [Guatemala]', 3, 0, 0, 0],
        [49, 'es-hn', 'Spanish [Honduras]', 3, 0, 0, 0],
        [50, 'es-mx', 'Spanish [Mexico]', 3, 0, 0, 0],
        [51, 'es-ni', 'Spanish [Nicaragua]', 3, 0, 0, 0],
        [52, 'es-pa', 'Spanish [Panama]', 3, 0, 0, 0],
        [53, 'es-pe', 'Spanish [Peru]', 3, 0, 0, 0],
        [54, 'es-pr', 'Spanish [Puerto Rico]', 3, 0, 0, 0],
        [55, 'es-py', 'Spanish [Paraguay]', 3, 0, 0, 0],
        [56, 'es-sv', 'Spanish [El Salvador]', 3, 0, 0, 0],
        [57, 'es-uy', 'Spanish [Uruguay]', 3, 0, 0, 0],
        [58, 'es-ve', 'Spanish [Venezuela]', 3, 0, 0, 0],
        [59, 'et', 'Estonian', 3, 0, 0, 0],
        [60, 'eu', 'Basque', 3, 0, 0, 0],
        [61, 'fa', 'Farsi', 3, 0, 0, 0],
        [62, 'fi', 'Finnish', 3, 0, 0, 0],
        [63, 'fo', 'Faeroese', 3, 0, 0, 1],
        [64, 'fr', 'French [Standard]', 3, 0, 0, 0],
        [65, 'fr-be', 'French [Belgium]', 3, 0, 0, 0],
        [66, 'fr-ca', 'French [Canada]', 3, 0, 0, 0],
        [67, 'fr-ch', 'French [Switzerland]', 3, 0, 0, 0],
        [68, 'fr-lu', 'French [Luxembourg]', 3, 0, 0, 0],
        [69, 'ga', 'Irish', 3, 0, 0, 0],
        [70, 'gd', 'Gaelic [Scotland]', 3, 0, 0, 0],
        [71, 'he', 'Hebrew', 3, 0, 0, 1],
        [72, 'hi', 'Hindi', 3, 0, 0, 0],
        [73, 'hr', 'Croatian', 3, 0, 0, 0],
        [74, 'hu', 'Hungarian', 3, 0, 0, 0],
        [75, 'id', 'Indonesian', 3, 0, 0, 0],
        [76, 'is', 'Icelandic', 3, 0, 0, 0],
        [77, 'it', 'Italian [Standard]', 3, 0, 0, 0],
        [78, 'it-ch', 'Italian [Switzerland]', 3, 0, 0, 0],
        [79, 'ja', 'Japanese', 3, 0, 0, 0],
        [80, 'ji', 'Yiddish', 3, 0, 0, 0],
        [81, 'ko', 'Korean', 3, 0, 0, 0],
        [82, 'lt', 'Lithuanian', 3, 0, 0, 0],
        [83, 'lv', 'Latvian', 3, 0, 0, 0],
        [84, 'mk', 'Macedonian [FYROM]', 3, 0, 0, 0],
        [85, 'ms', 'Malaysian', 3, 0, 0, 0],
        [86, 'mt', 'Maltese', 3, 0, 0, 0],
        [87, 'nl', 'Dutch [Standard]', 3, 0, 0, 0],
        [88, 'nl-be', 'Dutch [Belgium]', 3, 0, 0, 0],
        [89, 'no', 'Norwegian', 3, 0, 0, 0],
        [90, 'pl', 'Polish', 3, 0, 0, 0],
        [91, 'pt', 'Portuguese [Portugal]', 3, 0, 0, 0],
        [92, 'pt-br', 'Portuguese [Brazil]', 3, 0, 0, 0],
        [93, 'rm', 'Rhaeto-Romanic', 3, 0, 0, 0],
        [94, 'ro', 'Romanian', 3, 0, 0, 0],
        [95, 'ro-mo', 'Romanian [Republic of Moldova]', 3, 0, 0, 0],
        [96, 'ru', 'Russian', 3, 0, 0, 0],
        [97, 'ru-mo', 'Russian [Republic of Moldova]', 3, 0, 0, 0],
        [98, 'sb', 'Sorbian	', 3, 0, 0, 0],
        [99, 'sk', 'Slovak', 3, 0, 0, 0],
        [100, 'sl', 'Slovenian', 3, 0, 0, 0],
        [101, 'sq', 'Albanian', 3, 0, 0, 0],
        [102, 'sr', 'Serbian', 3, 0, 0, 0],
        [103, 'sv', 'Swedish', 3, 0, 0, 0],
        [104, 'sv-fi', 'Swedish [Finland]', 3, 0, 0, 0],
        [105, 'sx', 'Sutu', 3, 0, 0, 0],
        [106, 'sz', 'Sami [Lappish]', 3, 0, 0, 0],
        [107, 'th', 'Thai', 3, 0, 0, 0],
        [108, 'tn', 'Tswana', 3, 0, 0, 0],
        [109, 'tr', 'Turkish', 1, 1, 1, 0],
        [110, 'ts', 'Tsonga', 3, 0, 0, 0],
        [111, 'uk', 'Ukrainian', 3, 0, 0, 0],
        [112, 'ur', 'Urdu', 3, 0, 0, 1],
        [113, 've', 'Venda', 3, 0, 0, 0],
        [114, 'vi', 'Vietnamese', 3, 0, 0, 0],
        [115, 'xh', 'Xhosa', 3, 0, 0, 0],
        [116, 'zh-cn', 'Chinese [PRC]', 3, 0, 0, 0],
        [117, 'zh-hk', 'Chinese [Hong Kong SAR]', 3, 0, 0, 0],
        [118, 'zh-sg', 'Chinese [Singapore]', 3, 0, 0, 0],
        [119, 'zh-tw', 'Chinese [Taiwan]', 3, 0, 0, 0],
        [120, 'zu', 'Zulu', 3, 0, 0, 0]
    ];

    public function tableName()
    {
        return \App\Services\LanguageService::tableName();
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
            $table->char('code', 5)->comment('ISO 639-1 standard language codes');
            $table->char('name', 30);
            $table->unsignedTinyInteger('currency_id')->default(0);
            $table->unsignedTinyInteger('active')->default(0);
            $table->unsignedTinyInteger('sort')->default(0);
            $table->unsignedTinyInteger('direction')->default(0)->comment('0 LTR, 1 LTR');;

            $table->timestamps();

            $table->engine = 'InnoDB';

            $table->charset = 'utf8mb4';
            $table->collation = 'utf8mb4_unicode_ci';

            // Create indexes
            $indexNames = ['id', 'active', 'code', 'sort'];
            foreach ($indexNames as $newIndex) {
                $table->index($newIndex);
                $table->renameIndex($this->tableName() . '_' . $newIndex . '_index', $newIndex);
            }
        });

        \DB::statement("ALTER TABLE `" . $this->tableName() . "` comment 'ISO 639-2'");

        $this->insert();
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

    public function insert()
    {
        foreach(self::$languages as $language){
            DB::insert("INSERT INTO `" .  $this->tableName() . "` (`id`, `code`, `name`, `currency_id`, `active`, `sort`, `direction`) VALUES (".$language[0].", '".$language[1]."', '".$language[2]."', ".$language[3].", ".$language[4].", ".$language[5].", ".$language[6].")
            ;");
        }
    }
};
