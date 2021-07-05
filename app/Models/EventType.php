<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventType extends Model
{
    use HasFactory;

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
