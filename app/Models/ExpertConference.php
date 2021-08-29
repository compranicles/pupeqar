<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpertConference extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'service_conference_id',
        'conference_title',
        'partner_agency',
        'venue',
        'date_started',
        'date_ended',
        'present',
        'level_id',
        'document_description',
    ];
}
