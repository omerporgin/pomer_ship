<?php

namespace App\Services;

use App\Models\LocationCity as Item;
use App\Models\LocationCountry;
use App\Models\LocationState;

class LocationCityService extends abstractService
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
        $this->deletableMsg = 'Cities are not deletable';
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

        $list = $this
            ->item
            ->select([$tableName . '.id as id', $tableName . ".*", $stateTableName . ".name as state_name",
                $countryTableName .
                ".name as country"])
            ->leftJoin($stateTableName, $stateTableName . '.id', $tableName . '.state_id')
            ->leftJoin($countryTableName, $countryTableName . '.id', $stateTableName . '.country_id');

        $value = $filters->search['value'];

        // Search
        if ($value != '') {
            $search_text = explode(' ', $value);
            foreach ($search_text as $text) {
                $list = $list->orWhere(function ($list) use ($text, $tableName, $stateTableName, $countryTableName) {
                    $list
                        ->orWhere($countryTableName . '.name', 'LIKE', '%' . $text . '%')
                        ->orWhere($stateTableName . '.name', 'LIKE', '%' . $text . '%')
                        ->orWhere($tableName . '.name', 'LIKE', '%' . $text . '%')
                        ->orWhere($tableName . '.id', $text);
                });
            }
        }
        return [$list, $columns];
    }

    /**
     * @return LocationCountry|null
     */
    public function getCountry(): ?LocationCountry
    {
        if (!is_null($this->item)) {
            return LocationCountry::where('id', $this->item->country_id)->first();
        }
        return null;
    }

    /**
     * @return LocationState|null
     */
    public function getState(): ?LocationState
    {
        if (!is_null($this->item)) {
            return LocationState::where('id', $this->item->state_id)->first();
        }
        return null;
    }

    /**
     * Shows "Country > State > City" by using city_id
     * Have similar function for orders in Libraries/OrderLocation::class
     *
     * @param bool $isHtml
     * @return string
     */
    public function getFullName(bool $isHtml = true): string
    {

        $country = $this->getCountry();
        $countryName = '';
        if (!is_null($country)) {
            $countryName = $country->name;
        }

        $state = $this->getState();
        $stateName = '';
        if (!is_null($state)) {
            $stateName = $state->name;
        }
        if ($isHtml) {
            return '
                <div class="text-nowrap" style="font-size:0.8em">' . $stateName . " > " . $this->item->name . '</div>
                <div class="text-primary"><b>' . $countryName . '</b></div>';
        } else {
            return $countryName . " > " . $stateName . " > " . $this->item->name;
        }
    }

    /**
     * @param int $city_id
     * @param bool $isHtml
     * @return void
     */
    public static function staticGetFullName(int $city_id, bool $isHtml = true)
    {
        $service = service('LocationCity', $city_id);
        return $service->getFullName($isHtml);
    }

    /**
     * @param object $request
     * @return array[]
     */
    public function saveOrderCity(object $request): array
    {
        $state = service('LocationState', $request->state_id);
        if (!$state->hasItem()) {
            return [];
        }

        $newCity = Item::create([
            'name' => $request->city_name,
            'state_id' => $state->id,
            'is_accepted' => 0,
            'country_id' => $state->country_id,
            'country_code' => $state->country_code,
        ]);

        return ['city' => [
            'id' => $newCity->id,
            'text' => $newCity->name,
        ]];
    }

    /**
     * @param object $request
     * @return array
     */
    public function saveOrderState(object $request): array
    {
        $country = service('LocationCountry', $request->country_id);
        if (!$country->hasItem()) {
            return [];
        }

        $newState = LocationState::create([
            'name' => $request->state_name,
            'country_id' => $country->id,
            'is_accepted' => 0,
            'country_code' => $country->iso2,
        ]);

       if(is_null($newState)){
           return [];
       }

        $newCity = Item::create([
            'name' => $request->city_name,
            'state_id' => $newState->id,
            'is_accepted' => 0,
            'country_id' => $country->id,
            'country_code' => $country->iso2,
        ]);

        return [
            'city' => [
                'id' => $newCity->id,
                'text' => $newCity->name,
            ],
            'state' => [
                'id' => $newState->id,
                'text' => $newState->name,
            ]
        ];
    }
}
