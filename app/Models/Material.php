<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Material extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id';

    protected $fillable = [
        'department_id',
        'category',
        'level_id',
        'title',
        'author',
        'editor_name',
        'editor_profession',
        'volume',
        'issue',
        'date_publication',
        'copyright',
        'date_started',
        'date_completed',
        'document_description'
    ];
}
