<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacultyInterCountry extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'engagement_nature_id',
        'faculty_involvement_id',
        'host_name',
        'host_address',
        'host_type',
        'date_started',
        'date_ended',
        'document_description'
    ];
}
