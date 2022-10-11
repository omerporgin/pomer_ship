<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\UserGroupPriceService;
use App\Http\Requests\UserGroupPriceRequest;

class UserGroupPriceController extends Controller
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
