<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpertConsultant extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'service_consultant_id',
        'service_title',
        'service_category',
        'partner_agency',
        'venue',
        'date_started',
        'date_ended',
        'present',
        'level',
        'document_description',
    ];
}
