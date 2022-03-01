<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Authentication\UserRole;
use App\Models\Authentication\RolePermission;

class ToReceiveReportAuthorizationService {

    public function authorizeAction($permission_name) {
        $roles = DB::select("CALL get_roles_by_user_id(".auth()->id().")");
        
        foreach ($roles as $role) {
            $permission = RolePermission::where('role_permissions.role_id', $role->role_id)
                            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                            ->where('permissions.name', $permission_name)
                            ->first();

            return $permission !== null;

        }
    }

    public function authorizeReceiveIndividualResearch() {
        return $this->authorizeAction("receive individual reports (for consolidation by research)");
    }

    public function authorizeReceiveIndividualExtension() {
        return $this->authorizeAction("receive individual reports (for consolidation by extension)");
    }

    public function authorizeReceiveIndividualToDepartment() {
        return $this->authorizeAction("receive individual reports (for consolidation by department)");
    }

    public function authorizeReceiveIndividualToCollege() {
        return $this->authorizeAction("receive consolidated reports by department (for consolidation by college)");
    }

    public function authorizeReceiveIndividualToSector() {
        return $this->authorizeAction("receive consolidated reports by college (for final consolidation)");
    }

    public function authorizeReceiveIndividualToIpqmso() {
        return $this->authorizeAction("receive consolidated reports by all colleges (for final consolidation)");
    }

}