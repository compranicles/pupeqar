<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'event_types';

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'description',
        'status'
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }
}
