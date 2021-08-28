<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Officership extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id', 
        'organization',
        'faculty_officer_id',
        'position',
        'level_id',
        'organization_address',
        'date_started',
        'date_ended',
        'present',
        'documentdescription',
    ];
}
