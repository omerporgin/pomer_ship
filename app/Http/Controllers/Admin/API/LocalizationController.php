<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\LocalizationRequest;
use App\Services\LocalizationService;
use Illuminate\Http\Request;

class LocalizationController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(LocalizationService $service)
    {
        $this->service = $service;
    }


    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view(adminTheme() . '.localization');
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
        $this->service = service('Localization');

        try {
            $filters = [
                "start" => $request->start,
                "length" => $request->length,
                "search" => $request->search,
                "order" => $request->order,
                "lang" => langId()
            ];

            $list = $this->service->getAll($filters);

            $items = $list["list"];
            $data = [];
            foreach ($items as $item) {
                $item->deletable = $item->deletable;
                $data[] = array_merge($item->toArray(), [
                    'deletable' => $this->service->deletable($item->id),
                    'deletableMsg' => $this->service->deletableMsg,
                    'DestroyUrl' => route("admin_localization.destroy", $item->id),
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(LocalizationRequest $request)
    {

        $data = $this->service->filteredRequest($request);
        $responses = [];
        try {
            foreach ($data['value'] as $key => $value) {
                $lang = key($value);
                $responses[$key] = $this->service->save([
                    'id' => $key,
                    'lang' => $lang,
                    'value' => $value[$lang],
                ]);
            }

            return response()->json($responses, 200);

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
        $item = service('Localization', $id);

        $langData = [];

        if (is_null($variableItem = $this->service->getByVariable($item->variable))) {
            throw new \Exception('Item can\'t be initialzied : ' . $id);
        }
        $item->name = $item->variable;

        // lang data
        foreach ($variableItem as $item) {
            $langData[$item->lang] = (object)[
                'localizationID' => $item->id,
                'langId' => $item->lang,
                'variable' => $item->variable,
                'value' => $item->value,
            ];
        }

        return response()->view(adminTheme('forms.localization'), [
            'item' => $item,
            'isNew' => false,
            'updatable' => true,
            'langsAll' => langsAll(),
            'langData' => $langData,
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
        $item = service('Localization');

        // Localization vars can only be created by using localization page scan.)
        $langData = [];
        foreach (langsAll() as $lang) {
            $langData[$lang->id] = (object)[
                'localizationID' => '',
                'langId' => $lang->id,
                'variable' => '',
                'value' => '',
            ];
        }

        return response()->view(adminTheme('forms.localization'), [
            'item' => $item,
            'isNew' => true,
            'updatable' => true,
            'langsAll' => langsAll(),
            'langData' => $langData,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(LocalizationRequest $request, $id)
    {
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

    /**
     * scans for language variables
     *
     * @return void
     */
    public function scan()
    {
        try {
            $response = $this->service->scan();
        } catch (\Exception $e) {

            $errorList = $e->getMessage();
            if (!is_array($errorList)) {
                $errorList = [$errorList];
            }

            reportException($e);

            return response()->json($errorList, 500);
        }

        return response()->json($response, 200);
    }
}
