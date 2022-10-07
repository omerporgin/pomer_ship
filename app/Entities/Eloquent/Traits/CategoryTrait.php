<?php

namespace App\Entities\Eloquent\Traits;

use App\Models\Category;
use App\Models\CategoryData; 
use App\Services\CategoryDataService;
use App\Services\CategoryService;
use App\Services\CategoryLangService;

trait CategoryTrait
{

    public function category()
    {

        $item = $this->defaultCategory();
        $entity = entity('Category', $item->category_id);
        $entity->name = $entity->lang('name');
        return $entity;
    }
    /**
     * Default category of an item
     * 
     * Pages and Blogs have only default category
     */
    public function defaultCategory()
    {

        $type = $this->getType();
        $category = CategoryData::where('type', $type)
            ->where('type_id', $this->item->id)
            ->where('is_default', 1)
            ->first();

        if (!is_null($category)) {

            $category->tree =  implode(' > ',  $this->topCategoryNames($category->type_id));
        }

        return $category;
    }

    /**
     * Returns all categories of an item
     */
    public function allCategories($tree = false)
    {
        $cdTable = CategoryDataService::tableName();
        $cTable = CategoryService::tableName();
        $type = $this->getType();
        $returnItems = CategoryData::select($cdTable . '.category_id', $cdTable . '.is_default', $cTable . '.*')
            ->where($cdTable . '.type', $type)
            ->where($cdTable . '.type_id', $this->id)
            ->leftJoin($cTable, $cTable . '.id', $cdTable . '.category_id')
            ->get();

        foreach ($returnItems as $item) {
            if (!is_null($item->id)) {
                $item->tree =  implode(' > ',  $this->topCategoryNames($item->id));
            }
        }

        return $returnItems;
    }

    /**
     * Returns all category ids of this item
     */
    public function allCategoryIds()
    {
        return $this->allCategories()->pluck('category_id')->toArray();
    }

    /**
     * Top Categories of a category
     * 
     * @param int $id
     */
    public function top(int $id): ?object
    {
        $type =  $type = $this->getType();
        $list = CategoryData::where('type', $type)->where('category_id', $id)->orderBy('sort')->pluck('type_id')->toArray();

        return Category::whereIn('id', $list)->get();
    }

    /**
     * Sub Categories of a category
     * 
     * @param int $id
     */
    public function sub(?int $id = null, ?int $lang = null): ?object
    {

        if (is_null($id)) {
            $id = $this->item->id;
        }
        if (is_null($lang)) {
            $lang = $this->lang;
        }

        $c = CategoryService::tableName();
        $cd = CategoryDataService::tableName();
        $cl = CategoryLangService::tableName();

        $list = CategoryData::select($c . '.*', $cl . '.name as name', $cd . '.is_default as is_default',  $cd . '.id as sort_id')
            ->leftJoin($c, $c . '.id', $cd . '.type_id')
            ->leftJoin($cl, $c . '.id', $cl . '.type_id')
            ->where($cd . '.category_id', $id)
            ->where($cd . '.type', 3) // 3 is type id when we are looking for Subcategories of a category
            ->where($cl . '.lang', $lang)
            ->orderBy($cd . '.sort');

        return $list->get();
    }

    /**
     * Get all parent categories of a category.
     *
     * @param int $categoryID
     */
    public function getParents(int $categoryID): array
    {
        $type = $this->getType();

        $parentCategories = [];
        $categoryData = CategoryData::where('type', 3) // Must be 3 = category Type
            ->where('type_id', $categoryID)
            ->get();

        foreach ($categoryData as $item) {
            if (!is_null($obj = Category::find($item->category_id))) {
                $obj->is_default = $item->is_default;
                $obj->tree =  implode(' > ',  $this->topCategoryNames($item->category_id));
                $parentCategories[] = $obj;
            }
        }
        return $parentCategories;
    }

    /**
     * [ id, id, id, ...,  current_id ]
     * Be carefull this is a recursive function.
     * 
     * use result with array_reverse() function for top category to lower category.
     * 
     * @param int $id
     */
    public function topCategoryIds(int $id, $order = 'sort')
    {
        $type = $this->getType();
        $return = [$id];
        $c = CategoryService::tableName();
        $cd = CategoryDataService::tableName();

        $categoryData = CategoryData::select($cd . '.category_id as category_id')
            ->where($cd . '.type', 3) // must be 3 -> category type
            ->leftJoin($c, $c . '.id', $cd . '.type_id')
            //->where($c . '.type', $type) // we already know type of category by id 
            ->where($cd . '.type_id', $id)
            ->where($cd . '.is_default', 1)
            ->orderBy($cd . '.sort')
            ->first();
        
        if (!is_null($categoryData) and $categoryData->category_id > 0) {
            $return = array_merge($return, $this->topCategoryIds($categoryData->category_id));
        }
      
        return $return;
    }

    /**
     * @param int $id
     */
    public function topCategories(int $id)
    {
        $list = $this->topCategoryIds($id);

        return entities('Category', $list);
    }

    /**
     * [ main_category_name, sub_category_name, sub_category_name, ..., current_name ]
     * 
     * @param int $id
     */
    public function topCategoryNames(int $id): array
    {

        $tree = [];

        $topCategories = $this->topCategories($id);

        foreach ($topCategories as $topEntity) {
            $top = $topEntity->getItem();
            if (!is_null($top)) {
                $tree[] = $top->name;
            }
        }

        return $tree;
    }

    /**
     * Top Categories - only 3 level.
     */
    public function categoryTree(bool $active = false, ?int $lang = null): array
    {

        $type = $this->getType();
        $tree = [];

        $categoryEntity = app()->make(\App\Entities\Eloquent\Category::class);

        foreach ($this->mainCategories($active, $lang) as $main) {
            $sub = $this->sub($main->id);
            $list = [];

            foreach ($sub as $c) {
                $subsub = $this->sub($c->id);

                $llist = [];
                foreach ($subsub as $cc) {
                    $llist[] = (object)[
                        'item' => $cc,
                        'img' => $this->responsiveImg($cc->id, [
                            'b' => 'min-width:600px',
                            'm' => 'max-width:600px'
                        ]),
                        'link' => $categoryEntity->link($cc->id),
                        'list' => $this->sub($cc->id)
                    ];
                }

                $list[] = (object)[
                    'item' => $c,
                    'img' => $this->responsiveImg($c->id, [
                        'b' => 'min-width:600px',
                        'm' => 'max-width:600px'
                    ]),
                    'link' => $categoryEntity->link($c->id),
                    'list' => $llist
                ];
            }
            $tree[] = (object)[
                'item' => $main,
                'img' => $this->responsiveImg($main->id, [
                    'b' => 'min-width:600px',
                    'm' => 'max-width:600px'
                ]),
                'link' => $categoryEntity->link($main->id),
                'list' => $list,
            ];
        }

        return $tree;
    }

    /**
     * Get top categories of given $type
     * 
     * @param int $type -> DB::types
     */
    public function mainCategories(bool $active = false, ?int $lang = null): ?object
    {
        if (is_null($lang)) {
            $lang = $this->lang;
        }

        $type = $this->getType();

        $c = CategoryService::tableName();
        $cd = CategoryDataService::tableName();
        $cl = CategoryLangService::tableName();

        $list = CategoryData::select($c . '.*', $cl . '.name as name', $cd . '.is_default as is_default',  $cd . '.id as sort_id')
            ->leftJoin($c, $c . '.id', $cd . '.type_id')
            ->leftJoin($cl, $c . '.id', $cl . '.type_id')
            ->where($cd . '.category_id', 0)
            ->where($cl . '.lang', $lang)
            ->where($c . '.type', $type)
            ->where($cd . '.type', 3)
            ->orderBy($cd . '.sort');

        if ($active) {
            $list = $list->where($c . '.active', 1);
        }

        return $list->get();
    }
}
