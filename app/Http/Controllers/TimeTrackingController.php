<?php

namespace App\Http\Controllers;

use App\Models\StafifyUser;
use App\Models\StafifyTimeTracking;
use App\Models\StafifyShift;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class TimeTrackingController extends Controller
{
    public function index(Request $request)
    {
        $loggedUserId = Auth::id();
        $accessLevel = Auth::user()->access_level ?? 3;
        $today = Carbon::today();
        $currentMonth = $request->get('month', Carbon::now()->format('Y-m'));
        $selectedUserId = $request->get('user_id', 'all');

        // Get today's shifts for the logged-in user
        $todayShifts = $this->getUserTodayShifts($loggedUserId);
        
        // Get consolidated time tracking status for today
        $todayTimeTracking = $this->getUserConsolidatedTimeTrackingStatus($loggedUserId);
        
        // Check if user has active clock in
        $hasActiveClockIn = $this->hasActiveClockIn($loggedUserId);

        // Get time tracking records for display
        $startDate = Carbon::parse($currentMonth . '-01')->startOfMonth();
        $endDate = Carbon::parse($currentMonth . '-01')->endOfMonth();

        // For admin/manager, get all records; for regular users, get only their own
        if ($accessLevel == 2) {
            $timeTrackingRecords = $this->getTimeTrackingRecords($startDate, $endDate, $selectedUserId);
        } else {
            $timeTrackingRecords = $this->getUserTimeTrackingRecords($loggedUserId, $startDate, $endDate);
        }

        // Get stats for the current month (for admin/manager)
        $stats = $this->getTimeTrackingStats($startDate, $endDate, $accessLevel);

        // Get all users for filter dropdown (admin only)
        $users = [];
        if ($accessLevel == 2) {
            $users = StafifyUser::select('user_id', 'full_name')
                ->orderBy('full_name')
                ->get();
        }

        return view('time-tracking', compact(
            'todayShifts',
            'todayTimeTracking',
            'hasActiveClockIn',
            'timeTrackingRecords',
            'stats',
            'currentMonth',
            'selectedUserId',
            'users',
            'accessLevel'
        ));
    }

    public function clockIn(Request $request)
    {
        $request->validate([
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        $loggedUserId = Auth::id();
        $today = Carbon::today();

        // Check if user already has an active clock in
        if ($this->hasActiveClockIn($loggedUserId)) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active clock in session.'
            ]);
        }

        // Get today's shifts
        $todayShifts = $this->getUserTodayShifts($loggedUserId);
        if (empty($todayShifts)) {
            return response()->json([
                'success' => false,
                'message' => 'No shifts assigned for today.'
            ]);
        }

        // Create time tracking record
        $timeTracking = StafifyTimeTracking::create([
            'user_id' => $loggedUserId,
            'shift_id' => $todayShifts[0]['shift_id'] ?? null,
            'clock_in_time' => Carbon::now(),
            'record_date' => $today,
            'location' => $request->location,
            'notes' => $request->notes,
            'status' => 'incomplete'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully clocked in at ' . Carbon::now()->format('h:i A') . '.'
        ]);
    }

    public function clockOut(Request $request)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $loggedUserId = Auth::id();

        // Find active clock in
        $activeClockIn = StafifyTimeTracking::where('user_id', $loggedUserId)
            ->whereNull('clock_out_time')
            ->where('record_date', Carbon::today())
            ->first();

        if (!$activeClockIn) {
            return response()->json([
                'success' => false,
                'message' => 'No active clock in session found.'
            ]);
        }

        $clockOutTime = Carbon::now();
        $clockInTime = Carbon::parse($activeClockIn->clock_in_time);
        $totalHours = $clockInTime->diffInMinutes($clockOutTime) / 60;

        // Update the record
        $activeClockIn->update([
            'clock_out_time' => $clockOutTime,
            'total_hours' => $totalHours,
            'status' => 'completed',
            'notes' => $request->notes ?: $activeClockIn->notes
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully clocked out at ' . $clockOutTime->format('h:i A') . '.'
        ]);
    }

    private function getUserTodayShifts($userId)
    {
        return StafifyShift::where('user_id', $userId)
            ->where('shift_date', Carbon::today())
            ->orderBy('start_time')
            ->get()
            ->toArray();
    }

    private function getUserConsolidatedTimeTrackingStatus($userId)
    {
        $today = Carbon::today();
        
        $entries = StafifyTimeTracking::where('user_id', $userId)
            ->where('record_date', $today)
            ->orderBy('clock_in_time')
            ->get();

        if ($entries->isEmpty()) {
            return ['status' => null, 'entries' => [], 'total_hours' => 0];
        }

        $totalHours = 0;
        $status = 'incomplete';

        foreach ($entries as $entry) {
            if ($entry->clock_out_time) {
                $totalHours += $entry->total_hours;
            }
        }

        // Determine overall status
        $hasIncomplete = $entries->whereNull('clock_out_time')->isNotEmpty();
        if (!$hasIncomplete && $totalHours > 0) {
            $status = 'completed';
        }

        return [
            'status' => $status,
            'entries' => $entries->toArray(),
            'total_hours' => $totalHours
        ];
    }

    private function hasActiveClockIn($userId)
    {
        return StafifyTimeTracking::where('user_id', $userId)
            ->whereNull('clock_out_time')
            ->where('record_date', Carbon::today())
            ->exists();
    }

    private function getTimeTrackingRecords($startDate, $endDate, $selectedUserId = 'all')
    {
        $query = StafifyTimeTracking::with(['user'])
            ->join('stafify_shifts', 'stafify_time_tracking.shift_id', '=', 'stafify_shifts.shift_id')
            ->whereBetween('stafify_time_tracking.record_date', [$startDate, $endDate])
            ->select(
                'stafify_time_tracking.*',
                'stafify_shifts.start_time as shift_start',
                'stafify_shifts.end_time as shift_end',
                'stafify_shifts.shift_type',
                'stafify_shifts.location as shift_location',
                'stafify_users.full_name as employee_name'
            )
            ->join('stafify_users', 'stafify_time_tracking.user_id', '=', 'stafify_users.user_id')
            ->orderBy('stafify_time_tracking.record_date', 'desc')
            ->orderBy('stafify_time_tracking.clock_in_time', 'desc');

        if ($selectedUserId !== 'all') {
            $query->where('stafify_time_tracking.user_id', $selectedUserId);
        }

        return $query->get()->toArray();
    }

    private function getUserTimeTrackingRecords($userId, $startDate, $endDate)
    {
        return StafifyTimeTracking::with(['user'])
            ->join('stafify_shifts', 'stafify_time_tracking.shift_id', '=', 'stafify_shifts.shift_id')
            ->where('stafify_time_tracking.user_id', $userId)
            ->whereBetween('stafify_time_tracking.record_date', [$startDate, $endDate])
            ->select(
                'stafify_time_tracking.*',
                'stafify_shifts.start_time as shift_start',
                'stafify_shifts.end_time as shift_end',
                'stafify_shifts.shift_type',
                'stafify_shifts.location as shift_location',
                'stafify_users.full_name as employee_name'
            )
            ->join('stafify_users', 'stafify_time_tracking.user_id', '=', 'stafify_users.user_id')
            ->orderBy('stafify_time_tracking.record_date', 'desc')
            ->orderBy('stafify_time_tracking.clock_in_time', 'desc')
            ->get()
            ->toArray();
    }

    private function getTimeTrackingStats($startDate, $endDate, $accessLevel)
    {
        if ($accessLevel != 2) {
            return [
                'completed' => 0,
                'incomplete' => 0,
                'absent' => 0,
                'pending' => 0
            ];
        }

        $stats = StafifyTimeTracking::whereBetween('record_date', [$startDate, $endDate])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return [
            'completed' => $stats['completed'] ?? 0,
            'incomplete' => $stats['incomplete'] ?? 0,
            'absent' => $stats['absent'] ?? 0,
            'pending' => $stats['pending'] ?? 0
        ];
    }
}
