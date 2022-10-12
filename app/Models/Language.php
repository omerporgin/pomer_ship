<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{

    use HasFactory;

    protected $fillable = ['code', 'name', 'currency_id','active','sort','direction'];

    public static function all_langs()
    {
        if (\Schema::hasTable(\App\Models\langs::tableName())) {
            return \App\Models\langs::where('active', '1')->get();
        } else {
            return [];
        }
    }

    /**
     * Try to take browser languages
     */
    public static function getBrowserLang()
    {
        $browser_langs = self::GetClientPreferedLanguage(true);

        if (is_array($browser_langs)) {
            foreach ($browser_langs as $key => $val) {
                if (!is_null($lang = langs::where('id', $key)->where('active', '1')->first())) {
                    return $lang;
                    break;
                }
            }

            // If cant select language than  en-EN will be selected
            foreach ($browser_langs as $key => $val) {
                if (is_array($keyArr = explode('-', $key))) {
                    if (!is_null($lang = langs::where('id', $keyArr[0])->where('active', '1')->first())) {
                        return $lang;
                        break;
                    }
                }
            }
        }

        if (!is_null($lang = langs::where('active', 1)->first())) {
            return $lang;
        } else {
            // Bulamazsa türkçeyi alsın.
            if (!is_null($lang = langs::where('code', 'tr')->first())) {
                return $lang;
            } else {
                return null;
            }
        }
    }

    private static function GetClientPreferedLanguage($getSortedList = false, $acceptedLanguages = false)
    {
        // HTTP_ACCEPT_LANGUAGE might be declared
        if (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) {
            if (empty($acceptedLanguages)) {
                $acceptedLanguages = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
            }

            preg_match_all('/([a-z]{1,8}(-[a-z]{1,8})*)\s*(;\s*q\s*=\s*(1|0\.[0-9]+))?/i', $acceptedLanguages, $lang_parse);
            $langs = $lang_parse[1];
            $ranks = $lang_parse[4];

            $lang2pref = array();
            for ($i = 0; $i < count($langs); $i++) {
                $lang2pref[$langs[$i]] = (float)(!empty($ranks[$i]) ? $ranks[$i] : 1);
            }

            $cmpLangs = function ($a, $b) use ($lang2pref) {
                if ($lang2pref[$a] > $lang2pref[$b]) {
                    return -1;
                } elseif ($lang2pref[$a] < $lang2pref[$b]) {
                    return 1;
                } elseif (strlen($a) > strlen($b)) {
                    return -1;
                } elseif (strlen($a) < strlen($b)) {
                    return 1;
                } else {
                    return 0;
                }
                return 0;
            };

            uksort($lang2pref, $cmpLangs);

            if ($getSortedList) {

                if (is_array($lang2pref)) {
                    $new_list = [];
                    foreach ($lang2pref as $new_lang => $val) {
                        $new_lang = explode('-', $new_lang);
                        $new_list[] = $new_lang[0];
                    }
                    $new_list = array_unique($new_list);
                    return $new_list;
                } else {
                    return ['en'];
                }
            }

            reset($lang2pref);
            return key($lang2pref);
        } else {
            return 'en-EN';
        }
    }

    /**
     * Sets locale language to $locale
     *
     *  if $locale is NULL than than selects browser language
     *
     * @param int $locale -> \App\Models\lang::id
     */
    public static function set_locale($locale = NULL): void
    {

        if (is_null($locale)) {
            if (!is_null($borwserLang = self::getBrowserLang())) {
                $locale = $borwserLang->id;
            } else {
                $locale = \Config::get('app.locale_id');
            }
        }

        if (!is_null($locale_obj = \App\Models\langs::where('active', '1')->where('id', intVal($locale))->first())) {

            session([
                'lang' => $locale,
                'lang_code' => $locale_obj->code,
            ]);
        } else {
            echo 'Language Error';
            exit;
        }
    }

    /**
     * Returns default site language.
     */
    public static function default(): int
    {
        $default = \App\Models\langs::where('active', '1')->orderBy('sort')->first();
        if (!is_null($default)) {
            return $default->id;
        } else {
            return \Config::get('app.locale_id');
        }
    }

    /**
     * Used in : resources\lang\en\app.php
     *
     * @param int lang_id -> must be DB:app_config_lang.id
     */
    public static function lang_file($lang_id): array
    {
        $return = \Cache::remember('lang.' . $lang_id, app_config('cache_time_lang'), function () use ($lang_id) {
            return self::get_lang_file($lang_id);
        });
        return $return;
    }

    /**
     * Used in : resources\lang\en\app.php
     *
     * @param int lang_id -> must be DB:app_config_lang.id
     */
    public static function get_lang_file($lang_id): array
    {
        $return = [];

        $lang_data = \DB::select("SELECT * FROM " . \App\Models\configLocalization::tableName() . " WHERE lang=" . $lang_id . ";");
        if (!is_null($lang_data)) {
            foreach ($lang_data as $lang_variables) {
                if ($lang_variables->value != '') {
                    $value = $lang_variables->value;
                } else {
                    $value = str_replace('_', ' ', $lang_variables->variable);
                }
                $key = str_replace('app.', '', $lang_variables->variable);
                $key = strtolower($key);
                $return[$key] = $value;
            }
        }
        return $return;
    }

    /**
     * Used in : resources\lang\en\app.php
     *
     * @param int lang_id -> must be DB:app_config_lang.id
     */
    public static function activeLangs(): array
    {
        return \App\Models\langs::select('id')->where('active', 1)->get()->toArray();
    }
}
