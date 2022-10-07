<?php

namespace App\Http\Controllers\Vendor\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use Illuminate\Http\Request;
use App\Services\OrderPackageService;

class PackageController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(OrderPackageService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        return response()->view(vendorTheme() . '.packages', [
            'type' => $type
        ]);
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
            $data = parseBase64data($request->data);

            $filters = [
                'start' => $request->start,
                'length' => $request->length,
                'search' => $request->search,
                'type' => $data->type,
            ];

            if (isset($data->template)) {
                $filters["userType"] = $data->template;
            }
            if (isset($data->vendors)) {
                $filters["vendorList"] = $data->vendors;
            }

            $list = $this->service->getAll($filters);
            $items = $list["list"];
            $data = [];

            foreach ($items as $item) {
                $addData = $item->toArray();

                $addData['estimated_price'] = 123;
                $addData['deletable'] = $this->service->deletable($item->id);
                $addData['DestroyUrl'] = route("vendor_packages.destroy", $item->id);
                $data[] = $addData;
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->request->add([
            'id' => $id
        ]);
        return $this->store($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
