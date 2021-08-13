<?php

namespace App\Models;

use App\Models\EventType;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;
    use SoftDeletes;

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
        'event_type_id',
        'created_by',
        'modified_by'
    ];

    public function eventType() {
        return $this->hasOne(EventType::class);
    }

}

