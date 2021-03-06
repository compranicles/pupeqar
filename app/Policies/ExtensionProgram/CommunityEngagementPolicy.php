<?php

namespace App\Policies\ExtensionProgram;

use App\Models\CommunityEngagement;
use App\Models\User;
use App\Models\Authentication\UserRole;
use App\Models\Authentication\RolePermission;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\DB;

class CommunityEngagementPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function manage(User $user)
    {
        $roles = UserRole::where('user_roles.user_id', $user->id)
                 ->pluck('user_roles.role_id')->all();
            
        foreach ($roles as $role) {
            $permission = RolePermission::where('role_permissions.role_id', $role)
                            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                            ->where('permissions.name', "manage community engagements conducted by college/department")
                            ->first();

            if ($permission !== null) {
                return $permission !== null ;
            }
        }
    }
}
