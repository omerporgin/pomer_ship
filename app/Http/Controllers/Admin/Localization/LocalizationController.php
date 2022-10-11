<?php

namespace App\Http\Controllers\Admin\Localization;

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

}
