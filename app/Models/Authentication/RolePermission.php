<?php

namespace App\Models\Authentication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class RolePermission extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'role_id',
        'permission_id'
    ];

    public function role() {
        return $this->belongsTo(\App\Models\Role::class);
    }

    public function permission() {
        return $this->belongsTo(\App\Models\Authentication\Permission::class);
    }
}
