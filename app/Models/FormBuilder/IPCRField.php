<?php

namespace App\Models\FormBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPCRField extends Model
{
    use HasFactory;

    protected $fillable = [
        'i_p_c_r_form_id',
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

    public function ipcrform() {
        return $this->belongsTo(\App\Models\FormBuilder\IPCRForm::class);
    }
}
