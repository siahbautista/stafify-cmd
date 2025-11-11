<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\StafifyTimeTracking;
use App\Models\StafifyOvertime;
use App\Models\StafifyPayroll;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Total Employees
        $totalEmployees = User::where('is_archived', false)->count();
        
        // Active Employees
        $activeEmployees = User::where('is_archived', false)
            ->where('user_status', 'active')
            ->count();
        
        // Employees by Department
        $employeesByDept = User::where('is_archived', false)
            ->whereNotNull('user_dept')
            ->select('user_dept', DB::raw('count(*) as count'))
            ->groupBy('user_dept')
            ->orderBy('count', 'desc')
            ->get();
        
        // Employees by Engagement Status
        $employeesByEngagement = User::where('is_archived', false)
            ->whereNotNull('engagement_status')
            ->select('engagement_status', DB::raw('count(*) as count'))
            ->groupBy('engagement_status')
            ->get();
        
        // Employees by Branch Location
        $employeesByBranch = User::where('is_archived', false)
            ->whereNotNull('branch_location')
            ->select('branch_location', DB::raw('count(*) as count'))
            ->groupBy('branch_location')
            ->orderBy('count', 'desc')
            ->get();
        
        // Recent Hires (last 30 days)
        $recentHires = User::where('is_archived', false)
            ->where('employment_date', '>=', Carbon::now()->subDays(30))
            ->count();
        
        // Employees by User Type
        $employeesByType = User::where('is_archived', false)
            ->whereNotNull('user_type')
            ->select('user_type', DB::raw('count(*) as count'))
            ->groupBy('user_type')
            ->get();
        
        // Time Tracking Stats (if table exists)
        $timeTrackingCount = 0;
        $overtimeCount = 0;
        try {
            if (DB::getSchemaBuilder()->hasTable('stafify_time_tracking')) {
                $timeTrackingCount = StafifyTimeTracking::count();
            }
            if (DB::getSchemaBuilder()->hasTable('stafify_overtime')) {
                $overtimeCount = StafifyOvertime::count();
            }
        } catch (\Exception $e) {
            // Tables might not exist
        }
        
        // Monthly Hiring Trend (last 12 months)
        $monthlyHiring = User::where('is_archived', false)
            ->where('employment_date', '>=', Carbon::now()->subMonths(12))
            ->selectRaw('DATE_FORMAT(employment_date, "%Y-%m") as month, count(*) as count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Absent Records (last 30 days)
        $absentRecords = [];
        $absentByDate = [];
        try {
            if (DB::getSchemaBuilder()->hasTable('stafify_time_tracking')) {
                $absentRecords = StafifyTimeTracking::where('status', 'absent')
                    ->where('record_date', '>=', Carbon::now()->subDays(30))
                    ->selectRaw('DATE_FORMAT(record_date, "%Y-%m-%d") as date, count(*) as count')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                
                $absentByDate = [
                    'labels' => $absentRecords->pluck('date')->toArray(),
                    'data' => $absentRecords->pluck('count')->toArray(),
                ];
            }
        } catch (\Exception $e) {
            // Table might not exist
        }
        
        // Late Records (last 30 days) - clock in time after shift start time
        $lateRecords = [];
        $lateByDate = [];
        try {
            if (DB::getSchemaBuilder()->hasTable('stafify_time_tracking') && 
                DB::getSchemaBuilder()->hasTable('stafify_shifts')) {
                $lateRecords = DB::table('stafify_time_tracking as tt')
                    ->join('stafify_shifts as s', 'tt.shift_id', '=', 's.shift_id')
                    ->whereNotNull('tt.clock_in_time')
                    ->where('tt.record_date', '>=', Carbon::now()->subDays(30))
                    ->whereRaw('TIME(tt.clock_in_time) > s.start_time')
                    ->selectRaw('DATE_FORMAT(tt.record_date, "%Y-%m-%d") as date, count(*) as count')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                
                $lateByDate = [
                    'labels' => $lateRecords->pluck('date')->toArray(),
                    'data' => $lateRecords->pluck('count')->toArray(),
                ];
            }
        } catch (\Exception $e) {
            // Tables might not exist
        }
        
        // Prepare data for charts
        $chartData = [
            'departments' => [
                'labels' => $employeesByDept->pluck('user_dept')->toArray(),
                'data' => $employeesByDept->pluck('count')->toArray(),
            ],
            'engagement' => [
                'labels' => $employeesByEngagement->pluck('engagement_status')->toArray(),
                'data' => $employeesByEngagement->pluck('count')->toArray(),
            ],
            'branches' => [
                'labels' => $employeesByBranch->pluck('branch_location')->toArray(),
                'data' => $employeesByBranch->pluck('count')->toArray(),
            ],
            'userTypes' => [
                'labels' => $employeesByType->pluck('user_type')->toArray(),
                'data' => $employeesByType->pluck('count')->toArray(),
            ],
            'monthlyHiring' => [
                'labels' => $monthlyHiring->pluck('month')->toArray(),
                'data' => $monthlyHiring->pluck('count')->toArray(),
            ],
            'absent' => $absentByDate,
            'late' => $lateByDate,
        ];
        
        return view('dashboard', compact(
            'totalEmployees',
            'activeEmployees',
            'recentHires',
            'timeTrackingCount',
            'overtimeCount',
            'chartData'
        ));
    }
}
