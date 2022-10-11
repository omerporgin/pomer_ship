<?php

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class LanguageDataTableController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(LanguageService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param DatatableRequest  $request
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

                $data[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'code' => $item->code,
                    'currency' => $item->currency_id,
                    'active' => $item->active
                ];
            }

            return  [
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
