<?php

namespace App;

use App\Models\Cache;

class CacheHelper
{
    protected $model;

    public function __construct()
    {
        $this->model = \App::make(Cache::class);;
    }

    public function search($key)
    {
        $key = $this->keyWithPrefix($key);
        return $this->model::where('key', 'LIKE', $key . '%')->get();
    }

    /**
     * @param string $key
     * @return bool
     */
    public function deleteBykey(string $key): bool
    {
        $key = $this->keyWithPrefix($key);
        return $this->model::where('key', 'LIKE',  $key)->delete();
    }

    /**
     * @param string $key
     * @return string
     */
    private function keyWithPrefix(string $key): string
    {
        return 'laravel_cache_' . $key;
    }

}
