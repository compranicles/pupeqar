<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invention extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'invention_code';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $guarded = [];

    public function department() {
        return $this->belongsTo(\App\Models\Maintenance\Department::class);
    }
}
