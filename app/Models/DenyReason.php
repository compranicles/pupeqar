<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DenyReason extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'report_id',
        'user_id',
        'position_name',
        'reason'
    ];
}
