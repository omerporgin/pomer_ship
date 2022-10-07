<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Services\ShippingService;
use Illuminate\Http\Request;
use App\Http\Requests\ShippingRequest;

class ShippingController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    /**
     * @var imageService
     */
    protected $imageService;

    public function __construct(ShippingService $service)
    {
        $this->service = $service;
    }


    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view(adminTheme() . '.shippings');
    }

    /**
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param DatatableRequest $request
     * @return \Illuminate\Http\Response
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
                $data[] = array_merge($item->toArray(), [
                    'deletable' => $this->service->deletable($item->id),
                    'deletable_msg' => $this->service->deletableMsg,
                    'img' => $this->service->img($item->id),
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
     * @param ShippingRequest $request
     * @return Response
     */
    public function store(ShippingRequest $request)
    {

        $data = $this->service->filteredRequest($request);

        try {

            $response = $this->service->save($data);

            $this->service->saveBase64SingleImage($request->image, $response['id']);

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
        $item = service('Shipping', $id);

        return response()->view(adminTheme('forms.shipping'), [
            'item' => $item,
            'isNew' => false,
            'updatable' => true,
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
        $item = service('Shipping');

        return response()->view(adminTheme('forms.shipping'), [
            'item' => $item,
            'isNew' => true,
            'updatable' => true,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param ShippingRequest $request
     * @return Response
     */
    public function update(ShippingRequest $request, $id)
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
