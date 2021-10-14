<?php

namespace App\Models\FormBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchField extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_form_id',
        'label',
        'name',
        'placeholder',
        'size',
        'field_type_id',
        'dropdown_id', 
        'required',
        'visibility',
        'order',
        'is_active',
    ];
}
