<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExtensionServiceDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function extensionservice() {
        return $this->belongsTo(\App\Models\ExtensionService::class);
    }
}
