<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Partnership extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'title',
        'partner_type_id',
        'collab_nature_id',
        'collab_deliver_id',
        'target_beneficiary_id',
        'level_id',
        'date_started',
        'date_ended',
        'present',
        'contact_name',
        'contact_number',
        'contact_address',
        'document_description'
    ];
}
