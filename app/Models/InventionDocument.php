<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventionDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function invention() {
        return $this->belongsTo(\App\Models\Invention::class);
    }
}
