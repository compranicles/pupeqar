<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpertServiceConference extends Model
{
    use HasFactory, softDeletes;
    
    protected $primaryKey = 'id';

    protected $guarded = [];

    public function expertserviceconferencedocument() {
        return $this->hasMany(\App\Models\ExpertServiceConferenceDocument::class);
    }

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }
}
