<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GenerateTable extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = "id";

    protected $fillable = [
        'name',
        'is_table',
        'is_individual',
        'type_id',
        'report_category_id',
        'footers'
    ];
}
