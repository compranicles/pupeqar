<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtensionService extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function extensionservicedocument() {
        return $this->hasMany(\App\Models\ExtensionServiceDocument::class);
    }

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }
}
