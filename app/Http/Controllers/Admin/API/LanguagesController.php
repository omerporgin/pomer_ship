<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\LanguageRequest;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
    /**
     * @var service
     */
    protected $service;
    protected $imageService;

    public function __construct(LanguageService $languageService)
    {
        $this->service = $languageService;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view(adminTheme() . '.languages');
    }

    /**
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param DatatableRequest  $request
     * @return \Illuminate\Http\Response
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
                    'code' => $item->code,
                    'currency' => $item->currency_id,
                    'active' => $item->active
                ];
            }

            return  [
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
     * @param  LanguageRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(LanguageRequest $request)
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
        $item = service('Language', $id);

        $currencyService = service('Currency');

        return response()->view(adminTheme('forms.language'), [
            'item' => $item,
            'isNew' => false,
            'updatable' => true,
            'currencies' => $currencyService->getAll(),
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
        $item = service('Language');

        $currencyService = service('Currency');

        return response()->view(adminTheme('forms.language'), [
            'item' => $item,
            'isNew' => true,
            'updatable' => true,
            'currencies' => $currencyService->getAll(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(LanguageRequest $request, $id)
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
