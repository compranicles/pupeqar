<?php

namespace App\Models\FormBuilder;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Field extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'label',
        'name',
        'size',
        'field_type_id',
        'dropdown_id',
        'required',
        'order',
        'status',
    ];
}
