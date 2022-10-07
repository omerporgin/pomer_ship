<?php

namespace App\Entities\Eloquent\Traits;

use App\Models\Image;
use App\Entities\Eloquent\Type;

trait ImageTrait
{
    /**
     * Image path
     */
    public function path()
    {
        return storage_path() . env('IMAGE_PATH');
    }

    /**
     * Returns default image.
     *
     * @param int $type_id
     * @param string $sizes
     * @return string
     */
    public function img(string $size = 's'): string
    {
        $type = $this->type();

        $selfType = $this->getType();

        if (!is_null($list = Image::where('type', $selfType)->where('type_id', $this->id)->orderBy('sort')->orderBy('id')->first())) {
            return asset('/storage/images/resized/' . $list->id . $size . '.' . $type->ext);
        } else {
            return self::placeholder();
        }
    }

    /**
     * All images
     *
     * @param string $sizes
     * @return array
     */
    public function imgs(string $size = 's'): array
    {
        $return = [];

        $type = $this->type();
        $selfType = $this->getType();

        $images = Image::where('type', $selfType)
            ->where('type_id', $this->id)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        foreach ($images as $img) {
            $return[$img->id] = asset('/storage/images/resized/' . $img->id . $size . '.' . $type->ext);
        }
        return $return;
    }

    /**
     * Returns default resized image for responsive design.
     *
     * @param int $id
     * @param array $sizes
     * @return array
     */
    public function responsiveImgById(int $id, $sizes = ['t', 's', 'm', 'b', 'g']): array
    {
        $sources = [];
        $img = Image::where('id', $id)->first();

        if (!is_null($img)) {
            $type = $this->type();
            if (is_null($lang = $img->imageLang->where('lang', $this->lang)->first())) {
                $lang = (object)[
                    'title' => '',
                    'alt' => '',
                    'real_name' => '',
                ];
            }

            foreach ($sizes as $size => $ScreenSize) {
                $realName = null;
                if (!is_null($lang)) {
                    $realName = asset($lang->real_name . $size . '.' . $type->ext);
                }
                $sources[] = [
                    'img' => asset('/storage/images/resized/' . $id . $size . '.' . $type->ext),
                    'realName' => $realName,
                    'screenSize' => $ScreenSize,
                ];
            }
        }

        return [
            'sources' => $sources,
            'default' => asset('/storage/images/uploaded/' . $id . '.jpg'),
            'ext'  => $type->ext,
            'alt' => $lang->alt,
            'title' => $lang->title,
            'real_name' => $lang->real_name
        ];
    }

    /**
     * Returns default resized image for responsive design.
     *
     * @param int $ype_id
     * @param array $sizes
     * @return array
     */
    public function responsiveImg(int $type_id, $sizes = ['t', 's', 'm', 'b', 'g']): array
    {
        $return = [
            'sources' => [],
            'default' => self::placeholder()
        ];

        $type = $this->getType();

        $default = Image::where('type', $type)
            ->where('type_id', $type_id)
            ->orderBy('sort')
            ->orderBy('id')
            ->first();

        if (!is_null($default)) {
            $return =  $this->responsiveImgById($default->id, $sizes);
        }
        return $return;
    }

    /**
     * Imagelist in responsive form
     *
     * @param int $ype_id
     * @param array $sizes
     * @return array
     */
    public function responsiveImgs(int $type_id, array $sizes = ['t', 's', 'm', 'b', 'g']): array
    {

        $return = [];
        $type =  $this->getType();
        $images = Image::where('type', $type)
            ->where('type_id', $type_id)
            ->orderBy('sort')
            ->orderBy('id')
            ->get();

        foreach ($images as $image) {
            $return[] = $this->responsiveImgById($image->id, $sizes);
        }

        return $return;
    }

    /**
     * count of images ( reads from DB )
     *
     * @return ? must be int
     */
    public function countAllImgs()
    {
        $total = 0;

        $className = self::get_class_name();

        $all = self::imgs($className, $this->id, 's', 'resized');

        if (!is_null($all)) {
            if (is_countable($all)) {
                $total = count($all);
            }
        }
        return $total;
    }

    /**
     * Deprecated
     */
    public function hasImg(): bool
    {
        return FALSE;
    }

    /**
     * Returns sum of images.
     *
     * @param $id
     * @return mixed
     */
    public function getSumImages(Int $id = null): int
    {
        $type = $this->getType();

        if (is_null($id)) {
            $id = $this->id;
        }
        return Image::where('type', $type)
            ->where('type_id', $id)
            ->count();
    }

    /**
     *
     */
    public static function placeholder(): string
    {
        return '/img/placeholder.png';
    }

    /**
     * Returns data for image column used in data tables.
     */
    public function dataTableImg(int $id = null): array
    {
        return [
            'src' => $this->img('s'),
            'entity' => basename(__CLASS__),
            'count' => $this->getSumImages($id)
        ];
    }
}
