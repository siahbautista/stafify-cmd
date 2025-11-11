<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StafifyEvent extends Model
{
    use HasFactory;

    protected $table = 'stafify_events';
    protected $primaryKey = 'event_id';

    protected $fillable = [
        'created_by',
        'event_title',
        'event_date',
        'start_time',
        'end_time',
        'event_location',
        'event_type',
        'event_visibility',
        'event_description',
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    public function createdBy()
    {
        return $this->belongsTo(StafifyUser::class, 'created_by', 'user_id');
    }
}
