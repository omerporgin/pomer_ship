<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\LocationCountryRequest;
use App\Services\LocationCountryService;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;
use mysql_xdevapi\Exception;

class LocationCountryController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(LocationCountryService $service)
    {
        $this->service = $service;
    }

    /**
     * @return Response
     */
    public function index()
    {
        return response()->view(adminTheme() . '.location_country');
    }

    /**
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param DatatableRequest $request
     * @return Response
     */
    public function indexAjax(DatatableRequest $request)
    {
        try {
            $list = $this->service->getAll([
                "start" => $request->start,
                "length" => $request->length,
                "search" => $request->search,
                "order" => $request->order,
            ]);
            $data = [];
            foreach ($list["list"] as $item) {
                $data[] = array_merge($item->toArray(),[
                    'deletable' => $this->service->deletable($item->id),
                    'deletableMsg' => $this->service->deletableMsg,
                    'DestroyUrl' => route("admin_location_country.destroy", $item->id),
                ]);
            }

            return [
                'status' => 200,
                "total" => $list["total"],
                "data" => $data,
                "draw" => $request->draw,
                "recordsTotal" => $list["total"],
                "recordsFiltered" => $list["total"],
            ];
        } catch (\Exception $e) {

            reportException($e);

            return [
                'status' => 500,
                'error' => $e->getMessage(),
                'debug' => __CLASS__,
            ];
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LocationCountryRequest $request
     * @return Response
     */
    public function store(LocationCountryRequest $request)
    {
        $data = $this->service->filteredRequest($request);

        try {

            $response = $this->service->save($data);
            return response()->json($response, 200);

        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 500);

        }
    }

    /**
     * Created a new resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $item = service('LocationCountry', $id);

        return response()->view(adminTheme('forms.country'), [
            'item' => $item,
            'isNew' => false,
            'updatable' => true,
            'regionList' => LocationCountryService::getRegions(),
            'subRegionList' => LocationCountryService::getSubRegions(),
        ]);
    }

    /**
     * Created a new resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $item = service('LocationCountry');

        return response()->view(adminTheme('forms.country'), [
            'item' => $item,
            'isNew' => true,
            'updatable' => true,
            'regionList' => LocationCountryService::getRegions(),
            'subRegionList' => LocationCountryService::getSubRegions()
        ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param LocationCountryRequest $request
     * @return Response
     */
    public function update(LocationCountryRequest $request, $id)
    {
        $request->request->add([
            'id' => $id
        ]);
        return $this->store($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return Response
     */
    public function destroy($id)
    {
        throw new Exception('Not deletable');
    }
}
