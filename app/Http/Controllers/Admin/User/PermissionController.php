<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use Illuminate\Http\Request;
use App\Services\PermissionService;
use App\Http\Requests\PermissonRequest;

class PermissionController extends Controller
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
