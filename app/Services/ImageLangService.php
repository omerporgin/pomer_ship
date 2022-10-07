<?php

namespace App\Services;

use App\Models\ImageLang as Item;

class ImageLangService extends abstractService
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
     *
     * @return Array
     */
    public static function saveFormRules(int $id = null): array
    {
        $rules = [];

        return $rules;
    }

    /**
     * @return bool
     */
    public function deletable(int $id = null): bool
    {
        $this->deletableMsg = 'Item not deletable';
        // Üürn kontrolü gerekli.
        return true;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        $this->updatableMsg = 'Item not updatable';
        return true;
    }

}
