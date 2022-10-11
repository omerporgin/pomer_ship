<?php

namespace App\Http\Controllers\Admin\Language;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Http\Requests\LanguageRequest;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(LanguageService $service)
    {
        $this->service = $service;
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->view(adminTheme() . '.languages');
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
