<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class StafifyOvertime extends Model
{
    use HasFactory;

    protected $table = 'stafify_overtime';
    protected $primaryKey = 'ot_id';

    protected $fillable = [
        'user_id',
        'shift_id',
        'requested_date',
        'ot_date',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'status',
        'approved_by',
        'approved_date',
        'approval_notes',
    ];

    protected $casts = [
        'requested_date' => 'datetime',
        'ot_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'duration' => 'decimal:2',
        'approved_date' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(StafifyUser::class, 'user_id', 'user_id');
    }

    public function shift()
    {
        return $this->belongsTo(StafifyShift::class, 'shift_id', 'shift_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(StafifyUser::class, 'approved_by', 'user_id');
    }

    /**
     * Calculate overtime hours between two time points
     */
    public static function calculateOvertimeHours($startTime, $endTime)
    {
        $start = Carbon::parse($startTime);
        $end = Carbon::parse($endTime);
        
        // Handle case where end time is earlier than start time (next day)
        if ($end->lessThan($start)) {
            $end->addDay();
        }
        
        $duration = $start->diffInMinutes($end) / 60;
        return round($duration, 2);
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved requests
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected requests
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}
