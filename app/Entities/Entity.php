<?php

namespace App\Entities;

class Entity
{

    private static $defaultRepository;

    public function __construct()
    {
    }

    /**
     * 
     */
    public static function get(string $entityName, int $id = null)
    {
        try {
            self::$defaultRepository = config('repository.default');
            $class = "App\Entities\\" . self::$defaultRepository . "\\" . $entityName;
            return app()->make($class, [
                'id' => $id
            ]);
        } catch (\Exception $e) {
            return null;
        }
    }
}
