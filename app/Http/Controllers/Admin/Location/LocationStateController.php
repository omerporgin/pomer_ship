<?php

namespace App\Http\Controllers\Admin\Location;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\LocationStateRequest;
use App\Services\LocationStateService;
use Illuminate\Http\Request;
use \Illuminate\Http\Response;

class LocationStateController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(LocationStateService $service)
    {
        $this->service = $service;
    }

    /**
     * @return Response
     */
    public function index()
    {
        return response()->view(adminTheme() . '.location_state');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LocationStateRequest $request
     * @return Response
     */
    public function store(LocationStateRequest $request)
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
     * Created a new resource in storage.
     *
     * @param int $id
     * @return Response
     */
    public function show($id, Request $request)
    {
        $item = service('LocationState', $id);

        return response()->view(adminTheme('forms.state'), [
            'item' => $item,
            'isNew' => false,
            'updatable' => true,
            'typeList' => LocationStateService::getTypes()->toArray(),
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
        $item = service('LocationState');

        return response()->view(adminTheme('forms.state'), [
            'item' => $item,
            'isNew' => true,
            'updatable' => true,
            'typeList' => LocationStateService::getTypes()->toArray(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param LocationStateRequest $request
     * @return Response
     */
    public function update(LocationStateRequest $request, $id)
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
        throw new \Exception('Not deletable');
    }
}
