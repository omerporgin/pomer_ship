<?php

namespace App\Services\Traits;

use Intervention\Image\ImageManagerStatic;

trait ImageTrait
{

    /**
     * Returns default image.
     *
     * @param int $type_id
     * @param string $sizes
     * @return string
     */
    public function img(int $id = null, $withRoot = false): string
    {
        $imgs = $this->imgs($id, $withRoot);
        if (isset($imgs[0])) {
            return $imgs[0];
        } else {
            return '';
        }
    }

    /**
     * All images
     *
     * @param string $sizes
     * @return array
     */
    public function imgs(int $id = null, $withRoot = false): array
    {
        if (is_null($id)) {
            $id = $this->id;
        }
        $searchPath = self::imgPath() . $id . '.';
        $filesInPath = glob($searchPath . '*');

        if (!$withRoot) {
            array_walk($filesInPath, function (&$current, $key) {
                $current = 'storage/img/' . self::imgFolder() . '/' . basename($current);
            });
        }

        return $filesInPath;
    }

    /**
     * @return string
     */
    public static function imgFolder(): string
    {
        // This is the best solution with regards to performance.
        $className = strtolower((new \ReflectionClass(self::class))->getShortName());
        return str_replace(['service'], '', $className);
    }

    /**
     * @return string
     */
    public static function imgPath(): string
    {
        $path = 'app/public/img/' . self::imgFolder() . '/';
        $path = storage_path($path);

        return str_replace("\\", '/', $path);
    }

    /**
     * @param bool $withRoot
     * @return string
     */
    public static function resourceImgPath(bool $withRoot = true): string
    {
        $ImgDir = str_replace(['service'], '', strtolower(basename(get_class())));
        $path = 'app/public/img/' . $ImgDir . '/';
        if ($withRoot) {
            $path = storage_path($path);
        }

        return str_replace("\\", '/', $path);
    }

    /**
     * Yüklenecek resmin ismi. (id.no.webp -> resim ismindeki no parametresini verir.)
     *
     * @param $imgName
     * @return int
     */
    private static function getimgNo($imgName): int
    {
        $imgNo = 0;

        if (!empty($filesInPath = (new self)->imgs($imgName, true))) {
            $lastItem = $filesInPath[array_key_last($filesInPath)];
            $imgNo = explode('.', basename($lastItem));
            if (isset($imgNo[1])) {
                $imgNo = intVal($imgNo[1]);
                $imgNo++;
            }
        }

        return $imgNo;
    }

    /**
     *
     * @param $base64img
     * @param $imgName -> 'blog/'.$response['id']
     * @param $compression
     * @return \Intervention\Image\Image
     */
    function saveBase64Image($base64img, $imgName, $compression = 90)
    {
        if (is_null($base64img)) {
            return false;
        }

        // Img name
        $searchPath = self::imgPath() . $imgName . '.';
        $imgNo = self::getimgNo($imgName);

        // Img format
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64img));

        // Save & Delete if exists
        $imageName = storage_path('app/public/img/' . $this->imgFolder() . '/' . $imgName . '.' . $imgNo. '.webp');
        @unlink($imageName);

        $newImage = ImageManagerStatic::make($image);

        $newImage->resize(128, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('webp', $compression);
        return $newImage->save($imageName, $compression);
    }

    function saveBase64SingleImage($base64img, $imgName, $compression = 90)
    {
        if (is_null($base64img)) {
            return false;
        }

        // Img name
        $searchPath = self::imgPath() . $imgName . '.';

        // Img format
        $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64img));

        // Save & Delete if exists
        $imageName = storage_path('app/public/img/' . $this->imgFolder() . '/' . $imgName . '.webp');
        @unlink($imageName);

        $newImage = ImageManagerStatic::make($image);

        $newImage->resize(128, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('webp', $compression);
        return $newImage->save($imageName, $compression);
    }

    function saveImage($image, $imgName, $compression = 90)
    {

        try {
            $ext = $image->getClientOriginalExtension();
            if (!in_array($ext, ['jpeg', 'jpg', 'png', 'gif', 'tif', 'bmp', 'ico', 'psd', 'webp'])) {
                throw new \InvalidArgumentException('Wrong image format!');
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
        $newImage = ImageManagerStatic::make(file_get_contents($image));

        $newImage->resize(128, null, function ($constraint) {
            $constraint->aspectRatio();
        })->encode('webp', $compression);

        // Save & Delete if exists
        $imageName = storage_path('app/public/img/' . $this->imgFolder() . '/' . $imgName . '.webp');

        @unlink($imageName);
        return $newImage->save($imageName, $compression);
    }

    /**
     * @param $image
     * @return bool
     */
    public function deleteImage($image)
    {
        return unlink(self::imgPath() . $image);
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
