<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Research extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'research_class_id',
        'research_category_id',
        'research_agenda_id',
        'title',
        'researchers',
        'research_involve_id',
        'research_type_id',
        'keywords',
        'funding_type_id',
        'funding_amount',
        'funding_agency',
        'date_started',
        'date_targeted',
        'status',
        'date_completed',
        'document_description',
    ];
}
