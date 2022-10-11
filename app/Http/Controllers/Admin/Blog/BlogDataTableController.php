<?php

namespace App\Http\Controllers\Admin\Blog;

use App\Http\Controllers\Controller;
use App\Http\Requests\DatatableRequest;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogDataTableController extends Controller
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
     * Display a listing of the resource.
     *
     * dataTableJson
     *
     * @param DatatableRequest $request
     * @return Response
     */
    public function index(DatatableRequest $request)
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
}
