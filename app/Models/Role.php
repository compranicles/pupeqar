<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
    ];

    public function userrole() {
        return $this->hasMany(\App\Models\Authentication\UserRole::class);
    }

    public function rolepermission() {
        return $this->hasMany(\App\Models\Authentication\RolePermission::class);
    }
}
