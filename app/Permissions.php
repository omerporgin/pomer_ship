<?php

namespace App;

use App\Services\PermissionService;

class Permissions
{

    /**
     * For singleton instances
     */
    private static $instances = [];

    /**
     * @var PermissionService
     */
    protected $service;

    /**
     * @var array
     */
    protected $permissions;

    /**
     * @param obj $user
     */
    public function __construct($user = null)
    {

        if (is_null($user)) {
            $this->user = \Auth::user(); 
            $this->permissions =  $this->user->permission->permission;
            $this->permissions = json_decode($this->permissions);
        }
        $this->service = app(PermissionService::class);
    }

    /**
     * @param stirng $item
     * @param stirng $action
     * @return bool
     */
    public function has(string $item, string $action = 'see', ?object $user = null): bool
    {
        $instance = self::getInstance($user);
        
        if (isset($this->permissions->{$item . '_' . $action})) {
            return $this->permissions->{$item . '_' . $action};
        }
        return false;
    }

    /**
     * Return classname to disable link
     * 
     * @param stirng $item
     * @param stirng $action
     * @return string
     */
    public function class(string $item, string $action = 'see', ?object $user = null): string
    {
        $instance = self::getInstance($user);

        if ($this->has($item, $action, $user)) {
            return 'allowed';
        }
        return 'not_allowed';
    }

    /**
     * Exit if not allowed.
     * 
     * @param stirng $item
     * @param stirng $action
     * @return void
     */
    public function ifAllowed(string $item, string $action = 'see', ?object $user = null): void
    {
        $instance = self::getInstance($user);

        if (!$this->has($item, $action, $user)) {
            echo 'You dont have permission';
            exit;
        }
    }

    /**
     * The method you use to get the Singleton's instance.
     */
    public static function getInstance($user = null)
    {
        $subclass = static::class;
        if (!isset(self::$instances[$subclass])) {
            self::$instances[$subclass] = new static($user);
        }
        return self::$instances[$subclass];
    }
}
