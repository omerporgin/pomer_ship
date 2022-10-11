<?php

namespace App\Http\Controllers\Admin\Gtip;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Services\GtipService;
use Illuminate\Http\Request;

class GtipDataTableController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(GtipService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param DatatableRequest $request
     * @return \Illuminate\Http\Response
     */
    public function index(DatatableRequest $request)
    {
        try {
            $filters = [
                "start" => $request->start,
                "length" => $request->length,
                "search" => $request->search,
                "order" => $request->order,
            ];

            $list = $this->service->getAll($filters);
            $items = $list["list"];
            $data = [];
            foreach ($items as $item) {
                $item->deletable = $this->service->deletable($item->id);
                $data[] = $item;
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
