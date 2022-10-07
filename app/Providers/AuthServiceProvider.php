<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Services\PermissionService;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $permissions = PermissionService::getPermissionArray();

        foreach ($permissions as $key => $val) {
            Gate::define($key, function (User $user) use ($key) {
                $permissionList = $user->permission_list;
                // $user->permission_list[$key] ile çağırma. "Allowed memory size of ..." hatası verir.
                if (isset($permissionList[$key])) {
                    return $permissionList[$key] === 1;
                }
                return false;
            });
        }
    }
}
