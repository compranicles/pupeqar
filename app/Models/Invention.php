<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invention extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'invention_title',
        'invention_class_id',
        'collaborator',
        'date_started',
        'date_ended',
        'present',
        'invention_nature',
        'invention_status_id',
        'funding_agency',
        'funding_type_id',
        'funding_amount',
        'document_description'
    ];
}
