<?php

namespace App\Models\FormBuilder;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IPCRForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'label',
        'table_name',
        'is_active',
    ];

    public function ipcrfield() {
        return $this->hasMany(\App\Models\FormBuilder\IPCRField::class);
    }
}
