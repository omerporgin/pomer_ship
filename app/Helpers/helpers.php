<?php

use Intervention\Image\ImageManagerStatic;

if (!function_exists("langsAll")) {
    /**
     * All langs
     *
     * @return mixed
     */
    function langsAll()
    {
        return \App\Models\Language::where('active', '1')->orderBy('sort')->get();
    }
}

if (!function_exists("adminTheme")) {
    /**
     * Returns app theme
     *
     * @param $view
     * @return string
     */
    function adminTheme($view = null): string
    {
        $currentRoute = env('ADMIN_THEME');
        if (!is_null($view)) {
            $currentRoute = $currentRoute . '.' . $view;
        }
        return $currentRoute;
    }
}

if (!function_exists("vendorTheme")) {
    /**
     * Returns app theme
     *
     * @return string
     */
    function vendorTheme($view = null): string
    {
        $currentRoute = env('VENDOR_THEME');
        if (!is_null($view)) {
            $currentRoute = $currentRoute . '.' . $view;
        }
        return $currentRoute;
    }
}

if (!function_exists("theme")) {
    /**
     * Returns app theme
     *
     * @param string $path
     * @return string
     */
    function appTheme($view = null): string
    {
        $currentRoute = env('THEME');
        if (!is_null($view)) {
            $currentRoute = $currentRoute . '.' . $view;
        }
        return $currentRoute;
    }
}

if (!function_exists("theme")) {
    /**
     * Returns app theme
     *
     * @param string $path
     * @return string
     */
    function theme(string $path = ''): string
    {
        return appTheme($path);
    }
}

if (!function_exists("write_console")) {
    /**
     * Writes to console.
     *
     * @param string $msg
     * @return void
     */
    function write_console(string $msg)
    {
        $out = new \Symfony\Component\Console\Output\ConsoleOutput();
        $out->writeln("<info>" . $msg . "</info>");
    }
}

if (!function_exists("reportException")) {
    /**
     * Shows line and file of an error exception
     *
     * @param $e
     * @param $show
     * @return void
     */
    function reportException($e, $show = 0)
    {
        if ($show == 1) {
            dd("reportException", $e->getLine(), $e->getFile(), $e->getmessage());
        }
    }
}

if (!function_exists("_")) {
    /**
     * Language helper
     *
     * @param $variable
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    function _($variable = null)
    {
        return __('app.' . $variable);
    }
}


if (!function_exists("langCode")) {
    /**
     * @param $lang
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function langCode($lang)
    {
        $languageService = app()->make(\App\Services\LanguageService::class);
        return $languageService->current($lang);
    }
}

if (!function_exists("langId")) {
    /**
     * @param $currentCode
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function langId($currentCode = null)
    {
        if (is_null($currentCode)) {
            $currentCode = \App::getLocale();
        }
        $languageService = app()->make(\App\Services\LanguageService::class);
        return $languageService->currentId($currentCode);
    }
}

if (!function_exists("_")) {
    /**
     * Language helper
     *
     * @param $variable
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Translation\Translator|string|null
     */
    function _($variable = null)
    {
        return __('app.' . $variable);
    }
}

if (!function_exists("service")) {
    /**
     * Creates service
     *
     * @param $serviceName
     * @param int|null $id
     * @return mixed
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    function service($serviceName, int $id = null)
    {
        $serviceName = str_replace('Service', '', $serviceName);
        $serviceName = ucfirst($serviceName);

        $class = '\\App\\Services\\' . $serviceName . 'Service';
        return app()->make($class, [
            'id' => $id
        ]);
    }
}

if (!function_exists("services")) {
    /**
     * Creates an array of service list
     *
     * @param $serviceName
     * @param array $idList
     * @return array
     */
    function services($serviceName, array $idList): array
    {
        $serviceName = str_replace('Service', '', $serviceName);
        $serviceName = ucfirst($serviceName);

        $returnList = [];
        foreach ($idList as $id) {
            $returnList[] = service($serviceName, $id);
        }
        return $returnList;
    }
}

if (!function_exists("ifExistRoute")) {
    /**
     * If route exists returns route.
     *
     * @param null|string $routeName
     * @param array|int $parameters
     * @return string|null
     */
    function ifExistRoute(?string $routeName, array|int $parameters = null): ?string
    {
        if (is_null($routeName)) {
            return null;
        }
        if (Route::has($routeName)) {
            if (is_null($parameters)) {
                return route($routeName);
            } else {
                return route($routeName, $parameters);
            }
        }
        return null;
    }
}

if (!function_exists("base64data")) {
    /**
     * Used in data tables.
     *
     * @param $arr
     * @return string
     */
    function base64data($arr)
    {
        return base64_encode(json_encode($arr));
    }
}

if (!function_exists("parseBase64data")) {
    /**
     * Used in api controllers.
     *
     * @param $arr
     * @return string
     */
    function parseBase64data($data)
    {
        $data = base64_decode($data);
        return json_decode($data);
    }
}
