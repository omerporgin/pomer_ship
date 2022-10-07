<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use Illuminate\Http\Request;
use App\Services\PermissionService;
use App\Http\Requests\PermissonRequest;

class PermissionsController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view('admin.permissions', [
            'permissions' => new \App\Permissions,
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
                    'deletable' => $this->service->deletable($item->id),
                    'deletableMsg' => $this->service->deletableMsg,
                    'DestroyUrl' => route("admin_permissions.destroy", $item->id),
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
     * @param PermissonRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermissonRequest $request)
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
        $item = service('Permission', $id);

        return response()->view(adminTheme('forms.permissions'), [
            'item' => $item,
            'isNew' => false,
            'updatable' => true,
            'permissionList'=> $this->service::getPermissionArray(),
            'permission'=> json_decode($item->permission)
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
        $item = service('Permission');

        return response()->view(adminTheme('forms.permissions'), [
            'item' => $item,
            'isNew' => true,
            'updatable' => true,
            'permissionList'=> $this->service::getPermissionArray(),
            'permission'=> json_decode($item->permission)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PermissonRequest $request
     * @return Response
     */
    public function update(PermissonRequest $request, $id)
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

        try {

            $this->service->deleteById($id);
            return response()->json();

        } catch (\Exception $e) {

            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
