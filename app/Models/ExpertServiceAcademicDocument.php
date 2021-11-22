<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpertServiceAcademicDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function expertserviceacademic() {
        return $this->belongsTo(\App\Models\ExpertServiceAcademic::class);
    }
}
