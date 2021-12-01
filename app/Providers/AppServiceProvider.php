<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Authentication\UserRole;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('admin', function () {            
            $is_superadmin = UserRole::where('user_roles.user_id', auth()->id())
            ->where('user_roles.role_id', 9)
            ->first();
            
            // dd($is_superadmin);
            if ($is_superadmin == null) {
                return 1;
            }
        });
    }
}
