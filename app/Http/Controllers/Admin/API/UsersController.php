<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\DatatableRequest;

class UsersController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    /**
     * @var imageService
     */
    protected $imageService;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        return response()->view(adminTheme() . '.users', [
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
        $data = parseBase64data($request->data);

        try {
            $filters = [
                "start" => $request->start,
                "length" => $request->length,
                "search" => $request->search,
                "order" => $request->order,
                "type" => $data->type,
            ];

            $list = $this->service->getAll($filters);
            $items = $list["list"];
            $data = [];


            foreach ($items as $item) {
                $permissionName = service('Permission', $item->permission_id)?->name;
                $data[] = array_merge($item->toArray(), [
                    'permission_name' =>  $permissionName,
                    'deletable' => $this->service->deletable($item->id),
                    'deletableMsg' => $this->service->deletableMsg,
                    'DestroyUrl' => route("admin_users.destroy", $item->id),
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
     * @param UserRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
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

        $item = service('User', $id);

        $userGroupService = service('UserGroup');
        $permissisonService = service('Permission');

        $stateService = service('LocationState');
        $list = $stateService->getAll((object)[
            'country_id' => 225,
            'order' => [[
                'column' => 2,
                'dir' => 'asc'
            ]]
        ]);

        return response()->view(adminTheme('forms.user'), [
            'item' => $item,
            'isNew' => false,
            'gateList' => Gate::abilities(),
            'langsAll' => langsAll(),
            'permissionList' => $permissisonService->getAll(),
            'userGroupList' => $userGroupService->getAll(),
            'location' => '',
            'stateList' => $list['list'],
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
        $item = service('User');

        $userGroupService = service('UserGroup');
        $permissisonService = service('Permission');

        $stateService = service('LocationState');
        $list = $stateService->getAll((object)[
            'country_id' => 225,
            'order' => [[
                'column' => 2,
                'dir' => 'asc'
            ]]
        ]);

        return response()->view(adminTheme('forms.user'), [
            'item' => $item,
            'isNew' => true,
            'gateList' => Gate::abilities(),
            'langsAll' => langsAll(),
            'permissionList' => $permissisonService->getAll(),
            'userGroupList' => $userGroupService->getAll(),
            'location' => '',
            'stateList' => $list['list'],
            'updatable' => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserRequest $request
     * @return \Illuminate\Http\Response
     */
    //public function update(UserRequest $request, $id)
    public function update(UserRequest $request, $id)
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
