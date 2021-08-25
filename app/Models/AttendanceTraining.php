<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceTraining extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'title',
        'develop_class_id',
        'develop_nature_id',
        'budget',
        'funding_type_id',
        'organizer',
        'level_id',
        'venue',
        'date_started',
        'date_ended',
        'total_hours',
        'document_description'
    ];
}
