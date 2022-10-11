<?php

namespace App\Http\Controllers\Admin\Shipping;

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
