<?php

namespace App\Http\Controllers\Common\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Vendor\API\service;
use App\Services\OrderStatusService;
use Illuminate\Http\Request;
use function reportException;
use function response;

class OrderStatusesController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(OrderStatusService $orderService)
    {
        $this->service = $orderService;
    }

    public function index(Request $request)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function indexAjax(Request $request)
    {
        $type = $request->data;

        try {

            $data = [];
            $orderStatusList = $this->service->getAll();
            foreach ($orderStatusList['list'] as $item) {

                $toList = [];
                foreach (explode(",", $item->to) as $to) {
                    if ($to != '' and isset($orderStatusList['list'][$to])) {
                        $toList[] = [
                            'name' => $orderStatusList['list'][$to]['name'],
                            'color' => $orderStatusList['list'][$to]['color'],
                        ];
                    }
                }

                $fromList = [];
                foreach (explode(",", $item->from) as $from) {
                    if ($from != '' and isset($orderStatusList['list'][$from])) {
                        $fromList[] = [
                            'name' => $orderStatusList['list'][$from]['name'],
                            'color' => $orderStatusList['list'][$from]['color'],
                        ];
                    }
                }

                $item['to'] = $toList;
                $item['from'] = $fromList;
                $data[] = $item;
            }

            $total = count($orderStatusList);
            return [
                'status' => 200,
                "total" => $total,
                "data" => $data,
                "draw" => $request->draw,
                "recordsTotal" => $total,
                "recordsFiltered" => $total,
            ];
        } catch (\Exception $e) {

            reportException($e, true);

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
    public
    function store(Request $request)
    {

        $requiredFields = $this->service::requiredFields();
        $data = $request->only($requiredFields);

        try {
            $response = $this->service->save($data);
        } catch (\Exception $e) {

            $errorList = $e->getMessage();
            if (!is_array($errorList)) {
                $errorList = [$errorList];
            }

            reportException($e);

            return response()->json($errorList, 500);
        }
        return response()->json($response, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, $id)
    {
        $request->id = $id;
        $request->request->add(['id' => $id]);
        return $this->store($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function destroy($id)
    {
        //
    }
}
