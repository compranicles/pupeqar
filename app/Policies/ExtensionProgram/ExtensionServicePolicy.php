<?php

namespace App\Policies\ExtensionProgram;

use App\Models\ExtensionService;
use App\Models\User;
use App\Models\Authentication\RolePermission;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class ExtensionServicePolicy
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
                            ->where('permissions.name', "manage extension services")
                            ->first();

            return $permission !== null ;

        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExtensionService  $extensionService
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user)
    {
        return $this->viewAny($user);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $this->viewAny($user);
        
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExtensionService  $extensionService
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user)
    {
        return $this->viewAny($user);
        
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExtensionService  $extensionService
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user)
    {
        return $this->viewAny($user);
        
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExtensionService  $extensionService
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user)
    {
        return $this->viewAny($user);
        
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\ExtensionService  $extensionService
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user)
    {
        return $this->viewAny($user);
        
    }
}
