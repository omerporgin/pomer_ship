<?php

namespace App\Libraries;

use App\Models\Gtip;

/**
 * This class edits database after csv file loaded
 *
 *  dd( \App\Libraries\GtipDatabaseEdit::Edit() ); olarak çalıştırılabilir.
 */
class GtipDatabaseEdit
{
    /**
     *
     */
    public function __construct()
    {
        set_time_limit(0);

    }

    /**
     * @return void
     */
    public function edit()
    {

        // self::repairHyphens();

        // is_selectible (Only last section is selectible)
        $list = Gtip::orderBy('id', 'desc')->get();

        foreach ($list as $item) {
            if (count(explode('.', $item->gtip)) == 5) {

                $item->is_selectable = 1;
                $item->search = self::lookBack($item);
                $item->save();
            }
        }
    }

    /**
     * "- -" şeklindeki tireleri birleştirir "---"
     *
     * @return void
     */
    public static function repairHyphens()
    {
        $list = Gtip::orderBy('id', 'desc')->get();
        foreach ($list as $item) {
            $description = str_replace("- -", "--", $item->description);
            $description = str_replace("- -", "--", $description);
            $item->description = trim($description);
            $item->save();
        }
    }

    /**
     * Counts leadin chars of given stirng
     *
     * @param $haystack
     * @param $value
     * @return int
     */
    private static function countLeading($haystack, $value)
    {

        $i = 0;
        $mislead = false;
        while ($i < strlen($haystack) && !$mislead) {
            if ($haystack[$i] == $value) {
                $i += 1;
            } else {
                $mislead = true;
            }
        }
        return $i;
    }

    /**
     * @param $item
     * @return string
     */
    private static function lookBack($item)
    {
        $return = [];
        $itemLeading = self::countLeading($item->description, '-');
        $list = Gtip::where('id', '<', $item->id)->limit(200)->orderBy('id', 'desc')->get();

        // Önünde - yoksa kendinden önceki satırın - sayısından bir fazla olmalı.
        /*
        if ($itemLeading == 0) {
            if (!is_null($rowBefore = Gtip::where('id', ($item->id - 1))->first())) {
                $itemLeading = self::countLeading($rowBefore, '-') + 1;
            }
        }
        */
        foreach ($list as $newItem) {
            $currentLeading = self::countLeading($newItem->description, '-');
            if ($currentLeading == ($itemLeading - 1)) {
                $return[] = str_replace('-', '', $newItem->description);
                $itemLeading--;
            }
        }
        $return[] = $item->description;
        $return = array_reverse($return);


        return implode(' > ', $return);
    }
}
