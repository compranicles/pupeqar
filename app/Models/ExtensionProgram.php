<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExtensionProgram extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'program',
        'project',
        'activity',
        'extension_nature_id',
        'funding_type_id',
        'funding_amount',
        'extension_class_id',
        'others',
        'level_id',
        'date_started',
        'date_ended',
        'present',
        'status_id',
        'venue',
        'trainees',
        'trainees_class',
        'quality_poor',
        'quality_fair',
        'quality_satisfactory',
        'quality_vsatisfactory',
        'quality_outstanding',
        'timeliness_poor',
        'timeliness_fair',
        'timeliness_satisfactory',
        'timeliness_vsatisfactory',
        'timeliness_outstanding',
        'hours',
        'document_description',
    ];
}
