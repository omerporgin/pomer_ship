<?php

namespace App\Http\Controllers\Admin\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\BlogRequest;
use App\Http\Requests\DatatableRequest;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogsController extends Controller
{
    /**
     * @var service
     */
    protected $service;

    public function __construct(BlogService $blogService)
    {
        $this->service = $blogService;
    }

    /**
     * @return Response
     */
    public function index()
    {
        return response()->view(adminTheme() . '.blogs');
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

                $service = service('Blog', $item->id);

                $data[] = array_merge($item->toArray(), [
                    'language' => langCode($item->lang),
                    'deletable' => $service->deletable($item->id),
                    'MsgdeletableMsg' => $service->deletableMsg,
                    'DestroyUrl' => route("admin_blogs.destroy", $item->id),
                    'img' => $service->img(),
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
     * @param BlogRequest $request
     * @return Response
     */
    public function store(BlogRequest $request)
    {
        $data = $this->service->filteredRequest($request);

        try {

            $data['url'] = str_replace(' ', '-', $data['url']);
            $data['url'] = urlencode($data['url']);
            $response = $this->service->save($data);

            $this->service->saveBase64Image($request->image, $response['id']);

            return response()->json($response, 200);

        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 500);

        }
    }

    /**
     * Created a new resource in storage.
     *
     * @param int $id
     * @param Request $request
     * @return Response
     */
    public function show($id, Request $request)
    {
        $item = service('Blog', $id);

        return response()->view(adminTheme('forms.blog'), [
            'item' => $item,
            'isNew' => false,
            'updatable' => true,
            'langsAll' => langsAll(),
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
        $item = service('Blog');

        return response()->view(adminTheme('forms.blog'), [
            'item' => $item,
            'isNew' => true,
            'updatable' => true,
            'langsAll' => langsAll(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param BlogRequest $request
     * @param int $id
     * @return Response
     */
    public function update(BlogRequest $request, int $id)
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

    /**
     * @param Request $request
     * @return void
     */
    public function blogDeleteImage(Request $request)
    {
        if (!isset($request->file)) {
            throw new \Exception('Filename required.');
        }
        $parts = explode('.', $request->file);
        if (count($parts) != 3) {
            throw new \Exception('Wrong file name.');
        }

        return response()->json([
            'result' => service('Blog')->deleteImage($request->file)
        ]);

    }
}
