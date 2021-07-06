<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    //protected $table = 'events';

    //protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'description',
        'organizer',
        'sponsor',
        'start_date',
        'end_date',
        'location',
        'status',
        'event_type_id'
    ];

    public function eventType() {
        return $this->hasOne(EventType::class);
    }
}
