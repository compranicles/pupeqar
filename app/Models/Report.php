<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'sector_id',
        'college_id',
        'department_id',
        'format',
        'report_category_id',
        'report_code',
        'report_reference_id',
        'report_details',
        'report_documents',
        'report_date',
        'researcher_approval',
        'extensionist_approval',
        'chairperson_approval',
        'dean_approval',
        'sector_approval',
        'ipqmso_approval',
        'report_quarter',
        'report_year',
        'research_cluster_id'
    ];
}
