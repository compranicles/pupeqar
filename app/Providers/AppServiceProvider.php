<?php

namespace App\Providers;

use App\Models\Employee;
use App\Services\UserRoleService;
use Illuminate\Support\Facades\Blade;
use App\Models\Authentication\UserRole;
use Illuminate\Support\ServiceProvider;

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
        //IsReporting identifies if the user is faculty, admin, chairperson, or dean (because they have accomplishment reports to submit to diff. dept and colleges)
        Blade::if('IsReporting', function () {            
            $is_reporting = UserRole::where('user_roles.user_id', auth()->id())
            ->whereIn('user_roles.role_id', [1, 2, 3, 4, 5, 6])
            ->first();
            
            // dd($is_superadmin);
            if ($is_reporting != null) {
                return 1;
            }
            else {
                return 0;
            }
        });
        Blade::if('IsReceiving', function () {            
            $is_reporting = UserRole::where('user_roles.user_id', auth()->id())
            ->whereIn('user_roles.role_id', [5, 6, 7, 8])
            ->first();
            
            // dd($is_superadmin);
            if ($is_reporting != null) {
                return 1;
            }
            else {
                return 0;
            }
        });

        Blade::if('FacultyAdmin', function () {            
            $is_reporting = UserRole::where('user_roles.user_id', auth()->id())
            ->whereIn('user_roles.role_id', [1, 2, 3, 4])
            ->first();
            
            // dd($is_superadmin);
            if ($is_reporting != null) {
                return 1;
            }
            else {
                return 0;
            }
        });

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

        //not added in remote
        Blade::if('sectorHead', function () {            
            $is_vp = UserRole::where('user_roles.user_id', auth()->id())
            ->pluck('user_roles.role_id')->all();
            $i = 0;
                if (in_array(1, $is_vp) || in_array(3, $is_vp) || in_array(5, $is_vp) || 
                    in_array(6, $is_vp) || in_array(10, $is_vp) || in_array(11, $is_vp)){
                    $i++;
                    if (in_array(7, $is_vp) || in_array(8, $is_vp) || in_array(9, $is_vp)){
                        $i++;
                    }
                }

            if ($i == 1)
                return 0;
            else
                return 1;
        });

        Blade::if('ipqmso', function () {            
            $is_ipqmso = UserRole::where('user_roles.user_id', auth()->id())->pluck('user_roles.role_id')->all();
            
            $i = 0;
                if (in_array(1, $is_ipqmso) || in_array(3, $is_ipqmso) || in_array(5, $is_ipqmso) || 
                    in_array(6, $is_ipqmso) || in_array(10, $is_ipqmso) || in_array(11, $is_ipqmso)){
                    $i++;
                    if (in_array(7, $is_ipqmso) || in_array(8, $is_ipqmso) || in_array(9, $is_ipqmso)){
                        $i++;
                    }
                }

            if ($i == 1)
                return 0;
            else
                return 1;
        });

        Blade::if('ExceptSuperAdminAndSectorAndIpo', function () {            
            $is_ipqmso = UserRole::where('user_roles.user_id', auth()->id())
            ->pluck('user_roles.role_id')
            ->all();

            $i = 0;
                if (in_array(1, $is_ipqmso) || in_array(3, $is_ipqmso) || in_array(5, $is_ipqmso) || 
                    in_array(6, $is_ipqmso) || in_array(10, $is_ipqmso) || in_array(11, $is_ipqmso)){
                    $i++;
                    if (in_array(7, $is_ipqmso) || in_array(8, $is_ipqmso) || in_array(9, $is_ipqmso)){
                        $i++;
                    }
                }

            if ($i == 1)
                return 1;
            else
                return 0;
        });

        Blade::if('ExceptSuperAdmin', function () {            
            $is_ipqmso = UserRole::where('user_roles.user_id', auth()->id())
            ->whereIn('user_roles.role_id', [1, 3, 5, 6, 7, 8, 10, 11])
            ->first();
            
            // dd($is_superadmin);
            if ($is_ipqmso != null) {
                return 1;
            }
            else {
                return 0;
            }
        });

        Blade::if('Reviewer', function () {            
            $is_ipqmso = UserRole::where('user_roles.user_id', auth()->id())
            ->whereIn('user_roles.role_id', [5, 6, 7, 8, 10, 11])
            ->first();
            
            // dd($is_superadmin);
            if ($is_ipqmso != null) {
                return 1;
            }
            else {
                return 0;
            }
        });

        Blade::if('incompleteAccount', function () {
            $employee = Employee::where('user_id', auth()->id())->first();
            $roles = (new UserRoleService())->getRolesOfUser(auth()->id());
            if ($employee == null && (in_array(1, $roles) || in_array(3, $roles))) {
                request()->session()->flash('flash.banner', "Complete your account information. Click here.");
                return 1;
            }
            else {
                return 0;
            }
        });
    }
}
