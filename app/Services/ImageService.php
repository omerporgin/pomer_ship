<?php

namespace App\Services;

use App\Services\serviceRepositoryFactory;
use InvalidArgumentException;

use Intervention\Image\ImageManagerStatic as ImageManager;

use App\Models\Image as Item;
use App\Models\ImageLang;
use Illuminate\Support\Arr;

class ImageService extends abstractService
{
    use  Traits\LangTrait;

    /**
     * @var $typeRepository
     */
    protected $typeRepository;

    protected $root;

    protected $uploadPath;

    protected $resizedPath;

    // Image extensions
    public static $extensions = [
        'webp' => [],
        'jpg' => [],
        'jpeg' => [],
        'png' => [],
        'bmp' => [],
        'gif' => [],
    ];

    public static $sizeArr = ['t', 's', 'm', 'b', 'g'];


    /**
     * ImageService constructor.
     *
     * @param serviceRepositoryFactory $productRepository
     */
    public function __construct(int $id = null)
    {
        $this->setItem(new Item);

        $this->typeRepository = new TypeService;

        /**
         *   On windows try : $uploadPath = storage_path().'\\app/public/'.$real_dir.'\\';
         */
        $this->root = storage_path() . env('IMAGE_PATH');
        $this->uploadPath = $this->root . 'uploaded/';
        $this->resizedPath = $this->root . 'resized/';

        $this->checkDirs();
    }

    /**
     * Check for directories and creates if not exists
     *
     * @return void
     */
    private function checkDirs()
    {
        $requiredDirs = [
            $this->root,
            $this->uploadPath,
            $this->resizedPath,
        ];
        foreach ($requiredDirs as $dir) {
            \File::ensureDirectoryExists($dir);
        }
    }

    /**
     * @return bool
     */
    public function deletable(int $id = null): bool
    {
        return true;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        return true;
    }

    /**
     * Get image by id
     *
     * @param int $type
     * @param int $type_id
     * @return mixed
     */
    public function getByType(int $type, int $type_id)
    {
        return $this->item
            ->where('type', $type)
            ->where('type_id', $type_id)
            ->first();
    }

    /**
     * Get all images by type_id
     *
     * @param int $type
     * @param int $type_id
     * @return mixed
     */
    public function getAllByType(int $type, int $type_id)
    {
        return $this->item
            ->where('type', $type)
            ->where('type_id', $type_id)
            ->get();
    }

    /**
     *
     * @return Array
     */
    public static function saveFormRules(int $id = null): array
    {
        $rules = [
            'id' => 'nullable|integer',
            'type_id' => 'required|integer',
            'type' => 'required|integer',
        ];

        foreach (langsAll() as $lang_obj) {
            $lang = $lang_obj->id;
            $rules = array_merge($rules, [
                'real_name_' . $lang => 'nullable',
                'title_' . $lang => 'nullable',
                'alt_' . $lang => 'nullable',
            ]);
        }
        return $rules;
    }

    /**
     * Uploads image and resized image to their spesific folder.
     *
     * @param $item
     * @param $base64File
     * @return bool
     */
    public function upload($item, $base64File): bool
    {

        if (strlen($base64File) > 0) {

            $image = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64File));


            $imgPath = str_replace("\\", "/", $this->uploadPath . $item->id . '.jpg');

            @unlink($imgPath);

            file_put_contents($imgPath, $this->createImage($image));

            $this->resizeImages($item, $image);

            return TRUE;
        } else {
            // Nothing to upload
        }
        return FALSE;
    }

    /**
     *
     */
    protected function createImage($image): object
    {
        $newImage = ImageManager::make($image)->encode('jpg');

        // White background for transparent pngs
        $jpgImage = ImageManager::canvas($newImage->width(), $newImage->height(), '#ffffff');
        $jpgImage->insert($newImage)->encode('jpg', '100');
        return $jpgImage;
    }

    /**
     *
     */
    protected function resizeImages($item, $image)
    {
        $type = $item->type;
        $config = $this->ImageConfigData($type);

        foreach ($config->sizes as $sizeName => $size) {
            $resizedImagePath = str_replace("\\", "/", $this->resizedPath . $item->id . $sizeName . "." . $config->ext);
            $resizedImg = ImageManager::make($image);

            // Image width can't be lower than 20px
            if ($size < 20) {
                $size = 20;
            }
            $resizedImg->resize($size, null, function ($constraint) {
                $constraint->aspectRatio();
            })->encode($config->ext, $config->compression);

            @unlink($resizedImagePath); // Delete if exists

            $resizedImg->save($resizedImagePath, $config->compression);
        }
    }

    /**
     *
     */
    private function ImageConfigData(int $type): object
    {
        $standart_sizes = [
            'compression' => '90',
            'ext' => 'jpg',
            'sizes' => [
                't' => '50',
                's' => '100',
                'm' => '550',
                'b' => '900',
                'g' => '1200'
            ],
        ];

        if (!is_null($config = $this->typeRepository->getById($type))) {
            $standart_sizes = [
                'compression' => $config->compression,
                'ext' => $config->ext,
                'sizes' => [
                    't' => $config->t,
                    's' => $config->s,
                    'm' => $config->m,
                    'b' => $config->b,
                    'g' => $config->g,
                ],
            ];
        }
        return (object)$standart_sizes;
    }

    /**
     *
     * @param $data
     * @return array
     */
    public function sortImages($list): array
    {
        $result = true;
        $sort = 1;
        foreach ($list as $image) {
            $item = Item::find($image);
            $item->sort = $sort;
            if (!$item->save()) {
                $result = false;
            }
            $sort++;
        }
        return [
            'result' => $result
        ];
    }

    /**
     *
     */
    public static function placeholder(): string
    {
        return \URL::to('/img/placeholder.png');
    }

    /**
     *  Searches for Friendly Url
     *
     * @return Array
     */
    public function search(string $realName)
    {
        $site = str_replace(['https://', 'http://'], '', env('APP_URL'));
        $realName = str_replace(['https://', 'http://', $site], '', $realName);

        $realNameArr = explode('.', $realName);
        if (count($realNameArr) > 1) {
            $requestExt = array_pop($realNameArr);
            $realName = implode(".", $realNameArr);
            $size = substr($realName, -1);
            $realName = substr($realName, 0, -1);

            $imageLang = app()->make(ImageLangService::class);
            $type = app()->make(TypeService::class);

            $ilt = $imageLang::tableName();
            $it = self::tableName();
            $tt = $type::tableName();
            $img = ImageLang::select($tt . '.ext as ext', $it . '.id')
                ->where($ilt . '.real_name', $realName)
                ->leftJoin($it, $it . '.id', $ilt . '.type_id')
                ->leftJoin($tt, $tt . '.id', $it . '.type')
                ->first();
            if (!is_null($img)) {
                // Control 1
                if ($requestExt != $img->ext) {
                    abort(404);
                }

                // Control 2
                if (!in_array($size, ImageService::$sizeArr)) {
                    abort(404);
                }

                $file = $img->id . $size . '.' . $img->ext;
                $file = storage_path('app\\public\\images\\resized\\' . $file);
                return $file;
            } else {
                abort(404);
            }
        }
        return null;
    }

    /**
     * Deletes an img
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): array
    {
        return $this->repository->delete($id);
    }


    /**
     * Image files must be deleted
     */
    public function afterDelete(int $id)
    {

        foreach (self::$extensions as $ext => $val) {
            @unlink($this->uploadPath . $id . '.' . $ext);
            foreach (self::$sizeArr as $size) {
                @unlink($this->resizedPath . $id . $size . '.' . $ext);
            }
        }
        exit;
    }


    /**
     * Save image
     *
     * @param $data
     * @return Images
     */
    public function save($data): array
    {

        $dataForSave = Arr::only($data, $this->tableFieldNames());
        $result = parent::save($dataForSave);

        $id = $result['id'];

        foreach (langsAll() as $lang_obj) {
            $lang = $lang_obj->id;
            $imageLang = imageLang::where('type_id', $id)->where('lang', $lang)->first();
            if (is_null($imageLang)) {
                $imageLang = new imageLang;
                $imageLang->type_id = $id;
                $imageLang->lang = $lang;
            }
            if (isset($data['real_name_' . $lang])) {
                $imageLang->real_name = $data['real_name_' . $lang];
            }
            if (isset($data['title_' . $lang])) {
                $imageLang->title = $data['title_' . $lang];
            }
            if (isset($data['alt_' . $lang])) {
                $imageLang->alt = $data['alt_' . $lang];
            }

            $imageLang->save();
        }

        return $result;
    }
}
