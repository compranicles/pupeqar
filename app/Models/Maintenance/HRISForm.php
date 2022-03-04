<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HRISForm extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'label',
        'table_name',
        'is_active'
    ];
}
