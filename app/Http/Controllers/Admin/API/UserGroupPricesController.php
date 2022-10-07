<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\UserGroupPriceService;
use App\Http\Requests\UserGroupPriceRequest;

class UserGroupPricesController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(UserGroupPriceService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

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
        $data = parseBase64data($request->data);
        try {
            $filters = [
                "start" => $request->start,
                "length" => $request->length,
                "search" => $request->search,
                "order" => $request->order,
                'user_group' => $data->id // Required
            ];

            $list = $this->service->getAll($filters);
            $items = $list["list"];
            $data = [];

            foreach ($items as $item) {
                $item = $item->toArray();
                $item['deletable'] = $this->service->deletable($item['id']);
                $item['deletableMsg'] = $this->service->deletableMsg;
                $item['DestroyUrl'] = ifExistRoute('api_admin_user_group_prices.destroy', [
                    'api_admin_user_group_price' => $item['id']
                ]);
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

            reportException($e, 1);

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
     * @param UserGroupPriceRequest $request
     * @return Response
     */
    public function store(UserGroupPriceRequest $request)
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
     * Update the specified resource in storage.
     *
     * @param UserGroupPriceRequest $request
     * @return Response
     */
    public function update(UserGroupPriceRequest $request, $id)
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
        try {
            return response()->json([
                'result' => $this->service->deleteById($id),
                'err' => ''
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'result' => false,
                'err' => $e->getMessage()
            ], 500);
        }
    }
}
