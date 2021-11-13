<?php

namespace App\Policies\Maintenance\Research;

use App\Models\FormBuilder\ResearchForm;
use App\Models\User;
use App\Models\Authentication\UserRole;
use App\Models\Authentication\RolePermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResearchFormPolicy
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
        $roles = UserRole::where('user_roles.user_id', $user->id)
                 ->pluck('user_roles.role_id')->all();
        foreach ($roles as $role) {
            $permission = RolePermission::where('role_permissions.role_id', $role)
                            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                            ->where('permissions.name', "manage research forms")
                            ->first();

            return $permission !== null ;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FormBuilder\ResearchForm  $researchForm
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
        abort(404);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FormBuilder\ResearchForm  $researchForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, ResearchForm $researchForm)
    {
        abort(404);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\FormBuilder\ResearchForm  $researchForm
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, ResearchForm $researchForm)
    {
        abort(404);
    }

}
