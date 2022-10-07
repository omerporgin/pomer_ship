<?php

namespace App\Http\Controllers\Vendor\API;

use App\Http\Controllers\Admin\API\imageService;
use App\Http\Controllers\Admin\API\service;
use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\EntegrationDataRequest;
use App\Services\EntegrationDataService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use function entity;
use function reportException;
use function response;
use function route;

class EntegrationDataController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    /**
     * @var imageService
     */
    protected $imageService;

    public function __construct(EntegrationDataService $service)
    {
        $this->service = $service;
    }

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

                $entity = entity('Store', $item->id);

                $data[] = [
                    'id' => $item->id,
                    'img' => $entity->dataTableImg($item->id),
                    'name' => $item->company_name,
                    'email' => $item->email,
                    'phone' => $item->phone,
                    'status' => $item->status,
                    'deletable' => $this->service->deletable($item->id),
                    'deletableMsg' => $this->service->deletableMsg,
                    'DestroyUrl' => route("stores.destroy", $item->id),
                ];
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
     * @param EntegrationDataRequest $request
     * @return Response
     */
    public function store(EntegrationDataRequest $request)
    {
        // Logged user
        $request->request->add([
            'user_id' => Auth::id()
        ]);

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
     * @param EntegrationDataRequest $request
     * @return Response
     */
    public function update(EntegrationDataRequest $request, $id)
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

    /**
     * Created a new resource in storage.
     *
     * @param (int $id
     * @return Response
     */
    public function show(int $id, Request $request)
    {
        $item = \App\Models\EntegrationData::find($id);

        $entegration = service('Entegration', $item->entegration_id);

        return response()->view(vendorTheme('forms.entegrationData'), [
            'entegration' => $entegration,
            'item' => $item,
            'isNew' => false,
            'updatable' => true
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
        $entegration = service('Entegration', $request->data['id']);

        return response()->view(vendorTheme('forms.entegrationData'), [
            'isNew' => true,
            'entegration' => $entegration,
            'item' => null,
            'updatable' => true
        ]);
    }
}
