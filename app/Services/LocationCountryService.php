<?php

namespace App\Services;

use App\Models\LocationCountry as Item;
use phpDocumentor\Reflection\Types\Collection;
use \Illuminate\Database\Eloquent\Collection as EloquentCollection;

class LocationCountryService extends abstractService
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
        $this->deletableMsg = 'Countries are not deletable';
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
        $columns = ['id', 'name', 'iso3', 'iso2', 'capital', 'currency', 'region', 'subregion', 'is_accepted'];

        $this->validateFilters($filters, $columns);

        $list = $this->item;

        $value = $filters->search['value'];

        // Search
        if ($value != '') {
            $search_text = explode(' ', $value);
            foreach ($search_text as $text) {
                $list = $list->orWhere(function ($list) use ($tableName, $text) {
                    $list
                        ->orWhere($tableName . '.name', 'LIKE', '%' . $text . '%')
                        ->orWhere($tableName . '.id', $text)
                        ->orWhere($tableName . '.iso2', $text)
                        ->orWhere($tableName . '.iso3', $text);
                });
            }
        }
        return [$list, $columns];
    }

    /**
     * @return EloquentCollection|null
     */
    public static function getRegions(): ?EloquentCollection
    {

        $regions = \App\Models\LocationCountry::select('region')->distinct('region')->get();
        return $regions;
    }

    /**
     * @return EloquentCollection|null
     */
    public static function getSubRegions(): ?EloquentCollection
    {
        $subRegions = \App\Models\LocationCountry::select('subregion')->distinct('subregion')->get();
        return $subRegions;
    }

    /**
     * @param int $shippingId
     * @return int|null
     */
    public function getRegionByShippingId(int $shippingId): ?int
    {
        $region = match ($shippingId) {
            1 => $this->cargo_dhl_id,
            2 => $this->cargo_ups_id,
        };
        return $region;
    }
}
