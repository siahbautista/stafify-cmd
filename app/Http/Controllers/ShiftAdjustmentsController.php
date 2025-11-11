<?php

namespace App\Http\Controllers;

use App\Models\StafifyUser;
use App\Models\StafifyShift;
use App\Models\StafifyOvertime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ShiftAdjustmentsController extends Controller
{
    /**
     * Display the shift adjustments page
     */
    public function index(Request $request)
    {
        $loggedUserId = Auth::id();
        
        // Get user access level
        $user = StafifyUser::find($loggedUserId);
        if (!$user) {
            $mainUser = \App\Models\User::find($loggedUserId);
            if (!$mainUser) {
                return redirect()->route('login')->with('error', 'User not found.');
            }
            $accessLevel = $mainUser->access_level;
        } else {
            $accessLevel = $user->access_level;
        }

        // Initialize variables
        $message = '';
        $messageType = '';
        $overtimeRequests = collect();
        $pendingCount = 0;
        $employees = collect();
        $overtimeStats = [];
        $userShifts = collect();

        if ($accessLevel == 2) {
            // Manager view: get all overtime requests
            $overtimeRequests = $this->getAllOvertimeRequests();
            $pendingCount = $this->getPendingOvertimeCount();
            $employees = $this->getCompanyEmployees();
            $overtimeStats = $this->getOvertimeStatistics();
        } else {
            // Employee view: get only their own requests
            $overtimeRequests = $this->getUserOvertimeRequests($loggedUserId);
            $userShifts = $this->getUserShifts($loggedUserId);
        }

        // Handle tab parameter from URL
        $activeTab = $request->get('tab', 'requests');

        return view('shift-adjustments', compact(
            'overtimeRequests',
            'pendingCount',
            'employees',
            'overtimeStats',
            'userShifts',
            'accessLevel',
            'activeTab',
            'message',
            'messageType'
        ));
    }

    /**
     * Handle overtime request submission
     */
    public function requestOvertime(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'shift_id' => 'nullable|exists:stafify_shifts,shift_id',
            'ot_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'reason' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('shift-adjustments')
                ->withErrors($validator)
                ->with('error', 'Please check your input and try again.');
        }

        $loggedUserId = Auth::id();
        
        // Calculate duration
        $duration = StafifyOvertime::calculateOvertimeHours(
            $request->start_time, 
            $request->end_time
        );

        if ($duration <= 0) {
            return redirect()->route('shift-adjustments')
                ->with('error', 'End time must be after start time.');
        }

        try {
            StafifyOvertime::create([
                'user_id' => $loggedUserId,
                'shift_id' => $request->shift_id,
                'ot_date' => $request->ot_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'duration' => $duration,
                'reason' => $request->reason,
                'status' => 'pending',
                'requested_date' => now(),
            ]);

            return redirect()->route('shift-adjustments')
                ->with('success', 'Overtime request submitted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('shift-adjustments')
                ->with('error', 'Error submitting overtime request. Please try again.');
        }
    }

    /**
     * Handle overtime status update (approve/reject)
     */
    public function updateOvertimeStatus(Request $request)
    {
        $loggedUserId = Auth::id();
        
        // Check if user has manager access
        $user = StafifyUser::find($loggedUserId);
        if (!$user || $user->access_level != 2) {
            return redirect()->route('shift-adjustments')
                ->with('error', 'You don\'t have permission to approve/reject overtime requests.');
        }

        $validator = Validator::make($request->all(), [
            'ot_id' => 'required|exists:stafify_overtime,ot_id',
            'status' => 'required|in:approved,rejected',
            'approval_notes' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->route('shift-adjustments')
                ->withErrors($validator)
                ->with('error', 'Please check your input and try again.');
        }

        try {
            $overtime = StafifyOvertime::findOrFail($request->ot_id);
            
            $overtime->update([
                'status' => $request->status,
                'approved_by' => $loggedUserId,
                'approved_date' => now(),
                'approval_notes' => $request->approval_notes,
            ]);

            // Update shift table if approved
            if ($request->status === 'approved' && $overtime->shift_id) {
                StafifyShift::where('shift_id', $overtime->shift_id)
                    ->update([
                        'ot_modified' => true,
                        'ot_modified_by' => $loggedUserId,
                        'ot_modified_at' => now(),
                    ]);
            }

            $statusText = ucfirst($request->status);
            return redirect()->route('shift-adjustments')
                ->with('success', "Overtime request {$statusText} successfully.");
        } catch (\Exception $e) {
            return redirect()->route('shift-adjustments')
                ->with('error', 'Error updating overtime request. Please try again.');
        }
    }

    /**
     * Get all overtime requests (for managers)
     */
    private function getAllOvertimeRequests()
    {
        return StafifyOvertime::with(['user', 'shift', 'approvedBy'])
            ->join('stafify_users', 'stafify_overtime.user_id', '=', 'stafify_users.user_id')
            ->select(
                'stafify_overtime.*',
                'stafify_users.full_name as employee_name',
                'stafify_users.user_dept as department'
            )
            ->orderBy('stafify_overtime.requested_date', 'desc')
            ->get()
            ->map(function ($request) {
                return [
                    'ot_id' => $request->ot_id,
                    'employee_name' => $request->employee_name,
                    'department' => $request->department,
                    'ot_date' => $request->ot_date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'duration' => $request->duration,
                    'reason' => $request->reason,
                    'status' => $request->status,
                    'requested_date' => $request->requested_date,
                    'approver_name' => $request->approvedBy ? $request->approvedBy->full_name : null,
                    'approval_notes' => $request->approval_notes,
                ];
            });
    }

    /**
     * Get user's own overtime requests
     */
    private function getUserOvertimeRequests($userId)
    {
        return StafifyOvertime::with(['shift', 'approvedBy'])
            ->where('user_id', $userId)
            ->orderBy('requested_date', 'desc')
            ->get()
            ->map(function ($request) {
                return [
                    'ot_id' => $request->ot_id,
                    'ot_date' => $request->ot_date,
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'duration' => $request->duration,
                    'reason' => $request->reason,
                    'status' => $request->status,
                    'requested_date' => $request->requested_date,
                    'approver_name' => $request->approvedBy ? $request->approvedBy->full_name : null,
                    'approval_notes' => $request->approval_notes,
                ];
            });
    }

    /**
     * Get pending overtime requests count
     */
    private function getPendingOvertimeCount()
    {
        return StafifyOvertime::where('status', 'pending')->count();
    }

    /**
     * Get company employees (for managers)
     */
    private function getCompanyEmployees()
    {
        return StafifyUser::select('user_id', 'full_name', 'user_dept', 'user_position')
            ->where(function($query) {
                $query->whereNull('user_status')
                      ->orWhere('user_status', '!=', 'inactive')
                      ->orWhere('user_status', '!=', 'terminated');
            })
            ->orderBy('full_name')
            ->get();
    }

    /**
     * Get user shifts for overtime request form
     */
    private function getUserShifts($userId)
    {
        $startDate = Carbon::now()->subDays(15);
        $endDate = Carbon::now()->addDays(15);

        return StafifyShift::where('user_id', $userId)
            ->whereBetween('shift_date', [$startDate, $endDate])
            ->orderBy('shift_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get();
    }

    /**
     * Get overtime statistics for reports
     */
    private function getOvertimeStatistics()
    {
        $currentMonth = Carbon::now();
        $startOfMonth = $currentMonth->copy()->startOfMonth();
        $endOfMonth = $currentMonth->copy()->endOfMonth();

        // Overall statistics
        $overall = StafifyOvertime::whereBetween('ot_date', [$startOfMonth, $endOfMonth])
            ->selectRaw('
                COUNT(*) as total_requests,
                SUM(CASE WHEN status = "approved" THEN 1 ELSE 0 END) as approved_count,
                SUM(CASE WHEN status = "rejected" THEN 1 ELSE 0 END) as rejected_count,
                SUM(CASE WHEN status = "approved" THEN duration ELSE 0 END) as total_approved_hours
            ')
            ->first();

        // Department statistics
        $departments = StafifyOvertime::join('stafify_users', 'stafify_overtime.user_id', '=', 'stafify_users.user_id')
            ->whereBetween('stafify_overtime.ot_date', [$startOfMonth, $endOfMonth])
            ->where('stafify_overtime.status', 'approved')
            ->selectRaw('
                stafify_users.user_dept as department,
                SUM(stafify_overtime.duration) as total_hours,
                COUNT(DISTINCT stafify_overtime.user_id) as employee_count
            ')
            ->groupBy('stafify_users.user_dept')
            ->get();

        // Top employees
        $employees = StafifyOvertime::join('stafify_users', 'stafify_overtime.user_id', '=', 'stafify_users.user_id')
            ->whereBetween('stafify_overtime.ot_date', [$startOfMonth, $endOfMonth])
            ->where('stafify_overtime.status', 'approved')
            ->selectRaw('
                stafify_users.full_name,
                stafify_users.user_dept,
                SUM(stafify_overtime.duration) as total_hours,
                COUNT(*) as request_count
            ')
            ->groupBy('stafify_overtime.user_id', 'stafify_users.full_name', 'stafify_users.user_dept')
            ->orderBy('total_hours', 'desc')
            ->limit(10)
            ->get();

        return [
            'overall' => [
                'total_requests' => $overall->total_requests ?? 0,
                'approved_count' => $overall->approved_count ?? 0,
                'rejected_count' => $overall->rejected_count ?? 0,
                'total_approved_hours' => $overall->total_approved_hours ?? 0,
            ],
            'departments' => $departments,
            'employees' => $employees,
            'date_range' => [
                'start' => $startOfMonth->format('Y-m-d'),
                'end' => $endOfMonth->format('Y-m-d'),
            ],
        ];
    }
}
