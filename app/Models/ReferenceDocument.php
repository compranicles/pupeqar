<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferenceDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function reference() {
        return $this->belongsTo(\App\Models\Reference::class);
    }
}
