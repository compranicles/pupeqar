<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpertServiceAcademic extends Model
{
    use HasFactory, softDeletes;
    
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function expertserviceacademicdocument() {
        return $this->hasMany(\App\Models\ExpertServiceAcademicDocument::class);
    }

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }
}
