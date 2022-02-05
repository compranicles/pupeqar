<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GenerateType extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id";

    protected $fillable = [
        'name'
    ];
}
