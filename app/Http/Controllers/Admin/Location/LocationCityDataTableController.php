<?php

namespace App\Http\Controllers\Admin\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Services\LocationCityService;
use Illuminate\Http\Response;

class LocationCityDataTableController extends Controller
{

    /**
     * @var service
     */
    protected $service;

    public function __construct(LocationCityService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param DatatableRequest $request
     * @return Response
     */
    public function index(DatatableRequest $request)
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
}
