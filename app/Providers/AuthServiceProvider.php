<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\Authentication\RolePermission;
use App\Models\Authentication\UserRole;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // $this->generalPolicies();
        //
    }

    // public function generalPolicies() {
        // $roles = UserRole::where('user_roles.user_id', Auth::id())
        //          ->pluck('user_roles.role_id')->all();
        // foreach ($roles as $role) {
        //     $permissions = RolePermission::where('role_permissions.role_id', $role)
        //                     ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
        //                     ->pluck('permissions.name')->all();

        //     foreach ($permissions as $permission) {
        //         Gate::define('{{ $permission }}');
        //     }
        // }
    // }
}
