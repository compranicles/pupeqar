<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Authentication\UserRole;
use App\Models\Authentication\RolePermission;

class SingleAuthorizationService {

    public function authorizeAction($permission_name) {
        $roles = DB::select("CALL get_roles_by_user_id(".auth()->id().")");
        
        foreach ($roles as $role) {
            $permission = RolePermission::where('role_permissions.role_id', $role->role_id)
                            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                            ->where('permissions.name', $permission_name)
                            ->first();
            if ($permission !== null) {
                return $permission !== null ;
            }
        }
    }

    public function authorizeManageQuarterAndYear() {
        return $this->authorizeAction("manage quarter and year");
    }
}