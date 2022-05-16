<?php

namespace App\Services;

use App\Models\Authentication\UserRole;

class UserRoleService {
    public function getRolesOfUser($userID) {
        $roles = UserRole::where('user_id', $userID)->whereNull('deleted_at')->pluck('role_id')->all();
        return $roles;
    }

    public function getNumberOfUserByRole($roleID) {
        $count = UserRole::where('role_id', $roleID)->count();
        return $count;
    }
}