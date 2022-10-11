<?php

namespace App\Http\Controllers\Admin\Localization;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Services\LocalizationService;
use Illuminate\Http\Request;

class LocalizationDataTableController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(LocalizationService $service)
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
        $this->service = service('Localization');

        try {
            $filters = [
                "start" => $request->start,
                "length" => $request->length,
                "search" => $request->search,
                "order" => $request->order,
                "lang" => langId()
            ];

            $list = $this->service->getAll($filters);

            $items = $list["list"];
            $data = [];
            foreach ($items as $item) {
                $item->deletable = $item->deletable;
                $data[] = array_merge($item->toArray(), [
                    'deletable' => $this->service->deletable($item->id),
                    'deletableMsg' => $this->service->deletableMsg,
                    'DestroyUrl' => route("admin_localization.destroy", $item->id),
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
