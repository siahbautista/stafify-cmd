<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StafifyShift extends Model
{
    use HasFactory;

    protected $table = 'stafify_shifts';
    protected $primaryKey = 'shift_id';

    protected $fillable = [
        'user_id',
        'assigned_by',
        'shift_date',
        'start_time',
        'end_time',
        'shift_type',
        'location',
        'notes',
        'include_break',
        'break_duration_minutes',
        'ot_modified',
        'ot_modified_by',
        'ot_modified_at',
    ];

    protected $casts = [
        'shift_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'include_break' => 'boolean',
        'ot_modified' => 'boolean',
        'ot_modified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(StafifyUser::class, 'user_id', 'user_id');
    }

    public function assignedBy()
    {
        return $this->belongsTo(StafifyUser::class, 'assigned_by', 'user_id');
    }

    public function timeTracking()
    {
        return $this->hasMany(StafifyTimeTracking::class, 'shift_id', 'shift_id');
    }

    public function overtimeRequests()
    {
        return $this->hasMany(StafifyOvertime::class, 'shift_id', 'shift_id');
    }
}
