<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OngoingAdvanced extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'degree',
        'school',
        'accre_level_id',
        'support_type_id',
        'sponsor',
        'amount',
        'date_started',
        'date_ended',
        'present',
        'study_status_id',
        'units_earned',
        'units_enrolled',
        'document_description',
    ];
}
