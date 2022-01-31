

<?php

namespace App\Models\Maintenance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportColumn extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_category_id',
        'name',
        'table',
        'column',
        'is_active',
        'order',
    ];
}
