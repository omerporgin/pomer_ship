<?php

namespace App\Services;

use App\Models\Blog as Item;
use App\Models\Option;
use App\Services\Traits\ImageTrait;
use Cache;

class BlogService extends abstractService
{
    use ImageTrait;

    /**
     * Repository constructor.
     */
    public function __construct(int $id = null)
    {
        $this->setItem(new Item);
        parent::__construct($id);
    }

    /**
     * @return bool
     */
    public function deletable(int $id = null): bool
    {
        $id = $this->currentIfNull($id);

        if ($this->getPermissions()->has('content', 'save')) {
            return true;
        } else {
            $this->deletableMsg = 'You dont have permission';
        }
        return false;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        $id = $this->currentIfNull($id);

        if ($this->getPermissions()->has('content', 'save')) {
            return true;
        } else {
            $this->updatableMsg = 'You dont have permission';
        }
        return false;
    }

    /**
     * Create table data for item.
     *
     * @param object $filters
     * @return array
     */
    public function getAllFiltered(object $filters): array
    {

        $columns = ['id', '', 'headline', 'lang', 'active'];

        $filters = $this->validateFilters($filters);

        $list = $this->item;

        $value = $filters->search['value'];

        // Search
        if ($value != '') {
            $search_text = explode(' ', $value);
            foreach ($search_text as $text) {
                $list = $list->where(function ($list) use ($text) {
                    $list
                        ->orWhere('headline', 'LIKE', '%' . $text . '%')
                        ->orWhere('id', $text);
                });
            }
        }

        return [$list, $columns];
    }

    /**
     * @param array $data
     * @return array
     */
    public function saveIfNotExist(array $data): array
    {
        if (is_null(Item::where('headline', $data['headline'])->first())) {

            // insert news
            unset($data['id']);
            $savedItem = $this->save($data);
            $id = $savedItem['id'];
            // Insert images
            foreach ($data['imgList'] as $img) {
                $imageService = service('Image');
                $response = $imageService->save([
                    'type_id' => $id,
                    'type' => $this->getType(),
                ]);

                $type = pathinfo($img, PATHINFO_EXTENSION);
                $imgFile = 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents($img));
                $response['image_loaded'] = $imageService->upload($response['item'], $imgFile);
            }

            // inset category
            $data['category_default'][0] = 1;
            $this->updateCategories($data, $id);
            return $savedItem;
        }
        return [];
    }

    /**
     * Returns cache names
     *
     * @param int $id
     * @return array
     */
    protected function cacheNames(int $id): array
    {
        $cacheNames = [];
        foreach (langsAll() as $lang_obj) {
            $cacheNames[] = 'blog_single.' . $lang_obj->id . '.' . $id;
            foreach (['blog', 'blog-l'] as $view) {
                $cacheNames[] = 'blog.' . $lang_obj->id . '.' . $view . '.' . $id;
            }
        }

        return $cacheNames;
    }

    /**
     * @param int $id
     * @return void
     */
    public function onChange(int $id): void
    {
        foreach ($this->cacheNames($id) as $name) {
            Cache::forget($name);
        }
    }

    /**
     *
     */
    public function images()
    {
        return $this->responsiveImgs($this->id, [
            'b' => 'min-width:600px',
            'm' => 'max-width:600px'
        ]);
    }

    /**
     * Returns blogs avalible views
     *
     * @return array
     */
    public function getViewList(): array
    {
        $list = [];
        $viewStartsWith = 'blog_';
        $dir = "../resources/views/" . theme() . '/components/';
        foreach (glob($dir . $viewStartsWith . '*') as $view) {
            $list[] = str_replace([$viewStartsWith, '.blade.php'], '', basename($view));
        }

        return $list;
    }
}
