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
        // Blade::if('admin', function () {            
        //     $is_superadmin = UserRole::where('user_roles.user_id', auth()->id())
        //     ->where('user_roles.role_id', 9)
        //     ->first();
            
        //     // dd($is_superadmin);
        //     if ($is_superadmin == null) {
        //         return 1;
        //     }
        // });

        Blade::if('faculty', function () {            
            $is_faculty = UserRole::where('user_roles.user_id', auth()->id())
            ->whereIn('user_roles.role_id', [1, 2])
            ->first();
            
            // dd($is_superadmin);
            if ($is_faculty != null) {
                return 1;
            }
            else {
                return 0;
            }
        });

        Blade::if('admin', function () {            
            $is_admin = UserRole::where('user_roles.user_id', auth()->id())
            ->whereIn('user_roles.role_id', [3, 4])
            ->first();
            
            // dd($is_superadmin);
            if ($is_admin != null) {
                return 1;
            }
            else {
                return 0;
            }
        });

        Blade::if('chairperson', function () {            
            $is_chairperson = UserRole::where('user_roles.user_id', auth()->id())
            ->where('user_roles.role_id', 5)
            ->first();
            
            // dd($is_superadmin);
            if ($is_chairperson != null) {
                return 1;
            }
            else {
                return 0;
            }
        });

        Blade::if('director', function () {            
            $is_director = UserRole::where('user_roles.user_id', auth()->id())
            ->where('user_roles.role_id', 6)
            ->first();
            
            // dd($is_superadmin);
            if ($is_director != null) {
                return 1;
            }
            else {
                return 0;
            }
        });
    }
}
