<?php

namespace App\Http\Controllers;

use App\Models\LocationCity;
use App\Models\LocationCountry;
use App\Models\LocationState;
use App\Services\LocationCountryService;
use App\Services\LocationStateService;
use App\Services\LocationCityService;
use  Illuminate\Http\Request;
use App\Http\Requests\OrderCityAddRequest;
use App\Http\Requests\OrderStateAddRequest;

class LocationController extends Controller
{

    /**
     * @return void
     */
    public function selectSingleCountry(Request $request)
    {
        $data = [];
        $countryList = LocationCountry::where('name', 'LIKE', '%' . $request->search . '%')->get();
        foreach ($countryList as $country) {
            $data[] = [
                'id' => $country['id'],
                'text' => $country['name'],
                'html' => $country['name'],
            ];
        }

        return response()->json([
            'results' => $data,
            'pagination' => false,
        ]);
    }

    /**
     * @return void
     */
    public function selectSingleState(Request $request)
    {

        $data = [];
        $stateList = LocationState::where('name', 'LIKE', '%' . $request->search . '%')->where('country_id', $request->id)->get();
        foreach ($stateList as $state) {
            $data[] = [
                'id' => $state['id'],
                'text' => $state['name'],
                'html' => $state['name'],
            ];
        }

        return response()->json([
            'results' => $data,
            'pagination' => false,
        ]);
    }

    /**
     * @return void
     */
    public function selectSingleCity(Request $request)
    {

        $data = [];
        $cityList = LocationCity::where('name', 'LIKE', '%' . $request->search . '%')
            ->where('state_id', $request->id)
            ->orderBy('name')
            ->get();

        foreach ($cityList as $city) {
            $data[] = [
                'id' => $city['id'],
                'text' => $city['name'],
                'html' => $city['name'],

                // Data to complete partial location selections
                'full_text_name' => LocationCityService::staticGetFullName($city['id'], false)
            ];
        }

        return response()->json([
            'results' => $data,
            'pagination' => false,
        ]);
    }

    /**
     * @return void
     */
    public function selectCity(Request $request)
    {

        $data = [];

        $stateTable = LocationStateService::tableName();
        $cityTable = LocationCityService::tableName();
        $countryTable = LocationCountryService::tableName();

        $sql = LocationCity::select(['*', $cityTable . '.name as city_name', $cityTable . '.id as city_id',
            $stateTable . '.name as state_name', $countryTable . '.name as country_name'])
            ->leftjoin($stateTable, $stateTable . '.id', '=', $cityTable . '.state_id')
            ->leftjoin($countryTable, $countryTable . '.id', '=', $stateTable . '.country_id');

        // Search
        $sqlSearch = clone $sql;
        $sqlSearch = $sqlSearch->where(function ($query) use ($request, $countryTable, $cityTable) {
            $query
                ->where($cityTable . '.name', 'LIKE', '%' . $request->search . '%');
        });
        $sqlSearch = $sqlSearch->orderBy($countryTable . '.name')->orderBy($stateTable . '.name')->orderBy($cityTable . '.name');
        $sqlSearch = $sqlSearch->get()->toArray();

        // Eğer data 4 ten az ve şehir ise tüm şehri arama sonuna ekle
        if (count($sqlSearch) < 4 and !is_null($state = \App\Models\LocationState::where('name', $request->search)->first()
            )) {
            $sqlCities = clone $sql;
            $sqlCities = $sqlCities->where($cityTable . '.state_id', $state->id);
            $sqlCities = $sqlCities->orderBy($cityTable . '.name')->get()->toArray();
            $sqlSearch = array_merge($sqlSearch, $sqlCities);
        }

        foreach ($sqlSearch as $city) {
            $city = (object)$city;
            $data[] = [
                'id' => $city->city_id,
                'text' => $city->country_name . ' > ' . $city->state_name . ' > ' . $city->city_name,
                'html' => '<small><span class="text-danger">' . $city->country_name . '</span> > ' .
                    $city->state_name . '</small><br>
                    <span style="color:#000">' . $city->city_name . '</span>',

                // Data to complete partial location selections
                'country_id' => $city->country_id,
                'state_id' => $city->state_id,
                'country_name' => $city->country_name,
                'state_name' => $city->state_name,
                'city_name' => $city->city_name,
            ];
        }

        return response()->json([
            'results' => $data,
            'pagination' => false,
        ]);
    }

    /**
     * @param Request $request
     * @return void
     */
    public function apiAddCity(OrderCityAddRequest $request)
    {

        $data = (object)$request->All();
        return response()->json(service('LocationCity')->saveOrderCity($data));
    }

    /**
     * @param Request $request
     * @return void
     */
    public function apiAddState(OrderStateAddRequest $request)
    {
        $data = (object)$request->All();
        return response()->json(service('LocationCity')->saveOrderState($data));

    }

}
