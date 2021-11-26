<?php

namespace App\Models\FormBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicDevelopmentField extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_development_form_id',
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

    public function academicdevelopmentform() {
        return $this->belongsTo(\App\Models\FormBuilder\AcademicDevelopmentForm::class);
    }
}
