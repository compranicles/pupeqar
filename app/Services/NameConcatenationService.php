<?php

namespace App\Services;
use App\Models\User;

class NameConcatenationService {
    public function getConcatenatedNameByUserAndRoleName($user, $roleName) {
        if ($user != null) {
            $user = User::where('id', $user['id'])->first();
            if ($user['middle_name'] == null) {
                return strtoupper($user['first_name']).' '.strtoupper($user['last_name']);
            } else {
                return strtoupper($user['first_name']).' '.strtoupper($user['middle_name']).' '.strtoupper($user['last_name']);
            }
        } else {
            return '';
        }
    }
}