<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExpertServiceConsultant extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $guarded = [];

    // protected $table = 'expert_service_consultants';

    // protected $connection = 'mysql';

    public function expertserviceconsultantdocument() {
        return $this->hasMany(\App\Models\ExpertServiceConsultantDocument::class);
    }

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }
}
