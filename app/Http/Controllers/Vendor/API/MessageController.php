<?php

namespace App\Http\Controllers\Vendor\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Services\MessageService;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(MessageService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    /**
     * @param DatatableRequest $request
     * @return void
     */
    public function indexAjax(DatatableRequest $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
        //
    }
}
