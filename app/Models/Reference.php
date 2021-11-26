<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reference extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function referencedocument() {
        return $this->hasMany(\App\Models\ReferenceDocument::class);
    }

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }
}
