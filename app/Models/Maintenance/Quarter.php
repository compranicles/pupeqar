<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quarter extends Model
{
    use HasFactory;

    protected $fillable = [
        'current_quarter',
        'current_year'
    ];

    protected $primaryKey = "id";
}
