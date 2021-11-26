<?php

namespace App\Models\FormBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtensionProgramForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'table_name',
        'is_active',
    ];

    public function extensionprogramfield() {
        return $this->hasMany(\App\Models\FormBuilder\ExtensionProgramField::class);
    }
}
