<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invention extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $guarded = [];

    public function department() {
        return $this->belongsTo(\App\Models\Maintenance\Department::class);
    }

    public function inventiondocument() {
        return $this->hasMany(\App\Models\InventionDocument::class);
    }

    public function user() {
        return $this->belongsTo(\App\Models\User::class);
    }
}
