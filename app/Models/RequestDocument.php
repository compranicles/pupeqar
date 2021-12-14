<?php

namespace App\Models;

use App\Models\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function request() {
        return $this->belongsTo(\App\Models\Request::class);
    }
}
