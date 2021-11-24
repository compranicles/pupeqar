<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpertServiceConferenceDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function expertserviceconference() {
        return $this->belongsTo(\App\Models\ExpertServiceConference::class);
    }
}
