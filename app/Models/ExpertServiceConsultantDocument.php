<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class ExpertServiceConsultantDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function expertserviceconsultant() {
        return $this->belongsTo(\App\Models\ExpertServiceConsultant::class);
    }
}
