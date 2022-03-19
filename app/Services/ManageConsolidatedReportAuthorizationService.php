<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Models\Authentication\UserRole;
use App\Models\Authentication\RolePermission;

class ManageConsolidatedReportAuthorizationService {

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

    public function authorizeManageConsolidatedReportsByResearch() {
        return $this->authorizeAction("manage consolidated reports (by research)");
    }

    public function authorizeManageConsolidatedReportsByExtension() {
        return $this->authorizeAction("manage consolidated reports (by extension)");
    }

    public function authorizeManageConsolidatedReportsByDepartment() {
        return $this->authorizeAction("manage consolidated reports (by department)");
    }

    public function authorizeManageConsolidatedReportsByCollege() {
        return $this->authorizeAction("manage consolidated reports (by college)");
    }

    public function authorizeManageConsolidatedReportsBySector() {
        return $this->authorizeAction("manage consolidated reports (by all colleges)");
    }

    public function authorizeManageAllConsolidatedReports() {
        return $this->authorizeAction("manage consolidated reports (final)");
    }
}