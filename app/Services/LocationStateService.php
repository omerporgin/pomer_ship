<?php

namespace App\Services;

use App\Models\LocationState as Item;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use  Illuminate\Http\Request;

class LocationStateService extends abstractService
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
        $this->deletableMsg = 'States are not deletable';
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

        $tableName = self::tableName();
        $countryTableName = LocationCountryService::tableName();

        $columns = [$tableName . '.id', $countryTableName . '.iso2', $tableName . '.name', $tableName . '.name',
            $tableName . '.is_accepted'];

        $this->validateFilters($filters, $columns);

        $list = $this->item
            ->select([$tableName . '.id as state_id', $tableName . '.name as state_name', $countryTableName . ".*", $tableName . ".*",
                $countryTableName . ".name as country_name"])
            ->join($countryTableName, $countryTableName . '.id', '=', $tableName . '.country_id');

        if (isset($filters->country_id)) {
            $list = $list->where('country_id', $filters->country_id);
        }

        $value = '';
        if (isset($filters->search['value'])) {
            $value = $filters->search['value'];
        }

        // Search
        if ($value != '') {
            $search_text = explode(' ', $value);
            foreach ($search_text as $text) {
                $list = $list->orWhere(function ($list) use ($text, $tableName, $countryTableName) {
                    $list
                        ->orWhere($countryTableName . '.name', 'LIKE', '%' . $text . '%')
                        ->orWhere($tableName . '.name', 'LIKE', '%' . $text . '%')
                        ->orWhere($tableName . '.id', $text);
                });
            }
        }

        return [$list, $columns];
    }

    /**
     * @return EloquentCollection|null
     */
    public static function getTypes(): ?EloquentCollection
    {
        return \App\Models\LocationState::select('type')->distinct()->get();
    }

}
