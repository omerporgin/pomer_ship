<?php

namespace App\Services;

use File;
use Storage;
use App\Models\Language as Item;
use App\Models\Page;

class LanguageService extends abstractService
{
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
        if ($this->getPermissions()->has('setting', 'save')) {
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
        if ($this->getPermissions()->has('setting', 'save')) {
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

        $columns = ['id', 'name', 'active', 'currency_id', 'code', ''];
        $filters = (object)$filters;

        $this->validateFilters($filters, $columns);

        $list = $this->item;

        // Search
        if (isset($filters->search)) {
            $value = $filters->search['value'];
            if ($value != '') {
                $search_text = explode(' ', $value);
                foreach ($search_text as $text) {
                    $list = $list->orWhere(function ($list) use ($text) {
                        $list->orWhere('name', 'LIKE', '%' . $text . '%');
                    });
                }
            }
        }

        return [$list, $columns];
    }

    /**
     * Returns active languages
     */
    public function langsAll(): ?object
    {
        return \App\Models\Language::where('active', '1')->orderBy('sort')->get();
    }

    /**
     *
     */
    public function current($requestLangId)
    {
        if (is_null($requestLang = Item::where('id', $requestLangId)->first())) {

            $requestLangId = \Config::get('app.locale_id');
            if (is_null($requestLang = Item::where('id', $requestLangId)->first())) {
                die("lang problem");
            }
        }
        return $requestLang->code;
    }

    /**
     *
     */
    public function currentId($currentCode)
    {
        if (is_null($requestLang = Item::where('code', $currentCode)->first())) {
            return \Config::get('app.locale_id');
        } else {
            return $requestLang->id;
        }
    }

    /**
     *
     */
    public function activateLanguages($Langs)
    {
        $defaultLangPath = resource_path("lang\\en");

        foreach ($Langs as $lang) {
            $path = "..\\Resources\\lang\\" . $lang;
            if (!File::exists($path)) {
                File::makeDirectory($path, 0777, true, true);

                File::copyDirectory($defaultLangPath, $path);
                $fileContent = '<?php
                $service = app()->make(\App\Services\LocalizationService::class);
                return  $service->langVariables(' . langId($lang) . ');';
                File::put($path . '/app.php', $fileContent);
            }
        }

        foreach (Item::whereIn('code', $Langs)->get() as $item) {
            $item->active = 1;
            $item->save();
        }

        // Birşey döndürmüyor
        // return  $this->repository->activateLanguages($Langs);
    }

}
