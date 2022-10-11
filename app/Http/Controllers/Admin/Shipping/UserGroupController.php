<?php

namespace App\Http\Controllers\Admin\Shipping;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use Illuminate\Http\Request;
use App\Services\UserGroupService;
use App\Http\Requests\UserGroupRequest;

class UserGroupController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(UserGroupService $service)
    {
        $this->service = $service;
    }

    /**
     * @return void
     */
    public function index()
    {
        return response()->view(adminTheme('user_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserGroupRequest $request
     * @return Response
     */
    public function store(UserGroupRequest $request)
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
        $item = service('UserGroup', $id);

        $shippingservice = service('shipping');
        $allShippings = $shippingservice->getAll([]);

        return response()->view(adminTheme('forms.userGroup'), [
            'item' => $item,
            'isNew' => false,
            'updatable' => true,
            'shippingList'=>$allShippings['list'],
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
        $item = service('UserGroup');
        $shippingservice = service('shipping');
        $allShippings = $shippingservice->getAll([]);

        return response()->view(adminTheme('forms.userGroup'), [
            'item' => $item,
            'isNew' => true,
            'updatable' => true,
            'shippingList'=>$allShippings['list'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UserGroupRequest $request
     * @return Response
     */
    public function update(UserGroupRequest $request, $id)
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
