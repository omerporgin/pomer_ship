<?php

namespace App\Entities\Eloquent\Traits;

trait UrlTrait
{

    /**
     * permalink of item
     */
    public function permaLink(int $id, ?int  $lang = null): string
    {
        $route = (new static)->service->tableName(); 
        if (\Route::has($route)) {

            if (is_null($lang)) {
                $lang = $this->lang;
            }

            $route = route($route, [
                'id' => $id,
                'lang' => $lang
            ]);
            return $route;
        } else {
            // throw new \Exception("Dont have a route");
            return '';
        }
    }

    /**
     * html link of the model
     
     *  @param int $id
     * 
     *  @return string
     */
    public function link(int $id = null, ?int  $lang = null): string
    {

        if (is_null($id)) {
            $id = $this->id;
        }

        if (is_null($lang)) {
            $lang = $this->lang;
        }

        if (!empty($url = $this->friendlyUrl($id, $lang))) {
            return url($url);
        } else {
            return $this->permaLink($id, $lang);
        }
    }

    /**
     * html link of the model
     *
     *  @param int $lang
     *  @param string $id
     * 
     *  @return string
     */
    public function linkByView(string $view, int $lang): string
    {
        $langId = langId($lang);
        //$lang =  \App::getLocale();

        $page = $this->service->get();

        $page = $page::where('view', $view)->where('lang', $langId)->first();

        if (!is_null($page)) {
            return $this->link($langId, $page->id);
        }
        return '';
    }

    /**
     * alternate links of item
     * https://developers.google.com/search/docs/advanced/crawling/localized-versions
     *
     *  @param int $lang
     * 
     *  @return string
     */
    public function alternates(int $id): array
    {
        $return = [];
        foreach (langsAll() as $lang) {
            $return[] = (object)[
                'langCode' => $lang->code,
                'url' => $this->link($id, $lang->id),
                'id' => $lang->id,
            ];
        }

        return $return;
    }

    /**
     * 
     */
    public function friendlyUrl(int  $id=null, ?int  $lang = null): ?string
    {
        if (is_null($lang)) {
            $lang = $this->lang;
        }
        if (is_null($id)) {
            $id = $this->id;
        }
        $type = $this->getType();

        $url = \App\Models\Urls::select('url')->where('type', $type)->where('type_id', $id)->where('lang', $lang)->first();
        if (!is_null($url)) { 
            return $url->url;
        } else {
            return '';
        }
    }

    /**
     * Returns parameters to create seo header tags.
     * 
     * @param int $id
     * 
     * @return Array
     */
    public function seoData(int $id = null): array
    {
        if (is_null($id)) {
            $id = $this->id;
        }
        if(method_exists($this, 'lang')){
            $title = $this->lang('title');
            $description = $this->lang('description');
        }else{
            $title = '1';
            $description = '1';
        }
        return [
            'title' => $title,
            'description' => $description,
            'alternates' => $this->alternates($id),
        ];
    }
}
