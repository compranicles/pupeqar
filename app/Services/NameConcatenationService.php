<?php

namespace App\Services;
use App\Models\User;

class NameConcatenationService {
    public function getConcatenatedNameByUserAndRoleName($userID, $roleName) {
        $user = User::where('id', $userID)->first();
        if ($user != null) {
            if ($user->middle_name == null) {
                return strtoupper($user->first_name).' '.strtoupper($user->last_name);
            } else {
                return strtoupper($user->first_name).' '.strtoupper($user->middle_name).' '.strtoupper($user->last_name);
            }
        } else {
            return $roleName;
        }
    }
}