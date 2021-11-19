<?php

namespace App\Models\FormBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtensionProgramField extends Model
{
    use HasFactory;

    protected $fillable = [
        'extension_programs_form_id',
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

    public function extensionprogramform() {
        return $this->belongsTo(\App\Models\FormBuilder\ExtensionProgramForm::class);
    }

}
