<?php

namespace App\Models\FormBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventionForm extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'label',
        'table_name',
        'is_active',
    ];
}
