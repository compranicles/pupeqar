<?php

namespace App\Models\FormBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NonQarForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
    ];
}
