<?php

namespace App\Policies\Research;

use App\Models\Research;
use App\Models\User;
use App\Models\Authentication\UserRole;
use App\Models\Authentication\RolePermission;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;


class ResearchPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        $roles = DB::select("CALL get_roles_by_user_id($user->id)");
        
        foreach ($roles as $role) {
            $permission = RolePermission::where('role_permissions.role_id', $role->role_id)
                            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                            ->where('permissions.name', "view all faculty research")
                            ->first();

            return $permission !== null ;

        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        $roles = DB::select("CALL get_roles_by_user_id($user->id)");

        foreach ($roles as $role) {
        $permission = RolePermission::where('role_permissions.role_id', $role->role_id)
                        ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                        ->where('permissions.name', "manage faculty research registration")
                        ->first();

        return $permission !== null ;

        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        $roles = DB::select("CALL get_roles_by_user_id($user->id)");

        foreach ($roles as $role) {
            $permission = RolePermission::where('role_permissions.role_id', $role->role_id)
                            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                            ->where('permissions.name', "manage faculty research registration")
                            ->first();

            return $permission !== null ;

        }
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return $this->create($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Research  $research
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return $this->create($user);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * 
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function defer(User $user) {
        $roles = DB::select("CALL get_roles_by_user_id($user->id)");

        foreach ($roles as $role) {
            $permission = RolePermission::where('role_permissions.role_id', $role->role_id)
                            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                            ->where('permissions.name', "defer research")
                            ->first();

            return $permission !== null ;

        }
    }
}
