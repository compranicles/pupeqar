<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FacultyAchievement extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id', 
        'award_received',
        'faculty_award_id',
        'award_body',
        'level',
        'venue',
        'date_started',
        'date_ended',
        'document_description'
    ];
}
