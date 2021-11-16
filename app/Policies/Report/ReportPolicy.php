<?php

namespace App\Policies\Report;

use App\Models\User;
use App\Models\Report;
use App\Models\Authentication\UserRole;
use App\Models\Authentication\RolePermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function viewAnyFacultyReport(User $user) {
        $roles = UserRole::where('user_roles.user_id', $user->id)
                ->pluck('user_roles.role_id')->all();
        foreach ($roles as $role) {
        $permission = RolePermission::where('role_permissions.role_id', $role)
                        ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                        ->where('permissions.name', "manage faculty reports")
                        ->first();

        return $permission !== null ;

        }
    }

    public function submitFacultyReport(User $user) {
        return $this->viewAnyFacultyReport($user);
    }

    /***************************CHAIRPERSON******************************/
    public function viewAnyChairpersonReport(User $user) {
        $roles = UserRole::where('user_roles.user_id', $user->id)
                ->pluck('user_roles.role_id')->all();
        foreach ($roles as $role) {
        $permission = RolePermission::where('role_permissions.role_id', $role)
                        ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                        ->where('permissions.name', "manage chairperson reports")
                        ->first();

        return $permission !== null ;

        }
    }

    public function validateByChairperson(User $user) {
        return $this->viewAnyChairpersonReport($user);
    }

    /***************************DIRECTOR/DEAN******************************/
    
    public function viewAnyDeanReport(User $user) {
        $roles = UserRole::where('user_roles.user_id', $user->id)
                ->pluck('user_roles.role_id')->all();
        foreach ($roles as $role) {
        $permission = RolePermission::where('role_permissions.role_id', $role)
                        ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                        ->where('permissions.name', "manage director/dean reports")
                        ->first();

        return $permission !== null ;

        }
    }

    public function validateByDean(User $user) {
        return $this->viewAnyDeanReport($user);
    }

    /***************************SECTOR HEAD******************************/
        
    public function viewAnySectorHeadReport(User $user) {
        $roles = UserRole::where('user_roles.user_id', $user->id)
                ->pluck('user_roles.role_id')->all();
        foreach ($roles as $role) {
        $permission = RolePermission::where('role_permissions.role_id', $role)
                        ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                        ->where('permissions.name', "manage sector head reports")
                        ->first();

        return $permission !== null ;

        }
    }

    public function validateBySectorHead(User $user) {
        return $this->viewAnySectorHeadReport($user);
    }

    /***************************IPQMSO******************************/
        
    public function viewAnyIpqmsoReport(User $user) {
        $roles = UserRole::where('user_roles.user_id', $user->id)
                ->pluck('user_roles.role_id')->all();
        foreach ($roles as $role) {
        $permission = RolePermission::where('role_permissions.role_id', $role)
                        ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                        ->where('permissions.name', "manage IPQMSO reports")
                        ->first();

        return $permission !== null ;

        }
    }

    public function validateByIpqmso(User $user) {
        return $this->viewAnyIpqmsoReport($user);
    }

    /***************************TRACKING OF ALL REPORTS******************************/
        
    public function viewAny(User $user) {
        $roles = UserRole::where('user_roles.user_id', $user->id)
                ->pluck('user_roles.role_id')->all();
        foreach ($roles as $role) {
        $permission = RolePermission::where('role_permissions.role_id', $role)
                        ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                        ->where('permissions.name', "view all reports")
                        ->first();

        return $permission !== null ;

        }
    }
}
