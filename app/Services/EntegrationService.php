<?php

namespace App\Services;

use App\Models\Entegration as Item;

class EntegrationService extends abstractService
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
        $this->deletableMsg = 'Item not deletable';
        return false;
    }

    /**
     * @return bool
     */
    public function updatable(int $id = null): bool
    {
        $this->updatableMsg = 'Item not updatable';
        return true;
    }

    /**
     * Create table data for item.
     *
     * @param object $filters
     * @return array
     */
    public function getAllFiltered(object $filters): array
    {

        $tableName = self::tableName();
        $stateTableName = LocationStateService::tableName();
        $countryTableName = LocationCountryService::tableName();

        $columns = [
            $tableName . '.id',
            $countryTableName . '.name',
            $stateTableName . '.name',
            $tableName . '.name',
            $tableName . '.is_accepted',
        ];
        $filters = (object)$filters;

        $this->validateFilters($filters, $columns);

        $list = $this->item;

        $value = $filters->search['value'];

        // Search
        if ($value != '') {
            $search_text = explode(' ', $value);
            foreach ($search_text as $text) {

            }
        }
        return [$list, $columns];
    }
}
