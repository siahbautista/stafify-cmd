<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StafifyTimeTracking extends Model
{
    use HasFactory;

    protected $table = 'stafify_time_tracking';
    protected $primaryKey = 'record_id';

    protected $fillable = [
        'shift_id',
        'user_id',
        'clock_in_time',
        'clock_out_time',
        'record_date',
        'total_hours',
        'status',
        'location',
        'notes',
    ];

    protected $casts = [
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
        'record_date' => 'date',
        'total_hours' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(StafifyUser::class, 'user_id', 'user_id');
    }

    public function shift()
    {
        return $this->belongsTo(StafifyShift::class, 'shift_id', 'shift_id');
    }
}
