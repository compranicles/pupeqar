<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'college_id'
    ];

    public function college() {
        return $this->belongsTo(\App\Models\Maintenance\College::class);
    }

    public function research() {
        return $this->hasOne(\App\Models\Research::class);
    }
}
