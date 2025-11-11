<?php

namespace App\Http\Controllers;

use App\Models\StafifyUser;
use App\Models\StafifyShift;
use App\Models\StafifyEvent;
use App\Models\StafifyCompanySetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ShiftManagementController extends Controller
{
    /**
     * Display the shift management page
     */
    public function index()
    {
        $loggedUserId = Auth::id();
        
        // First try to find in stafify_users table
        $user = StafifyUser::find($loggedUserId);
        
        // If not found in stafify_users, try the main users table
        if (!$user) {
            $mainUser = \App\Models\User::find($loggedUserId);
            if (!$mainUser) {
                return redirect()->route('login')->with('error', 'User not found.');
            }
            
            // Create a temporary user object with the same structure
            $user = (object) [
                'user_id' => $mainUser->user_id,
                'access_level' => $mainUser->access_level,
                'company' => $mainUser->company ?? 'Default Company'
            ];
        }

        $accessLevel = $user->access_level;
        
        // Get users for assignment (only for access level 2)
        $users = [];
        if ($accessLevel == 2) {
            $users = StafifyUser::select('user_id', 'full_name', 'user_dept', 'user_position')
                ->where(function($query) {
                    $query->whereNull('user_status')
                          ->orWhere('user_status', '!=', 'inactive')
                          ->orWhere('user_status', '!=', 'terminated');
                })
                ->orderBy('full_name')
                ->get();
        }

        // Get shifts based on access level
        $shifts = $this->getCompanyShifts($loggedUserId, $accessLevel);
        
        // Get events based on access level
        $events = $this->getCompanyEvents($loggedUserId, $accessLevel);

        // Get company settings
        $companySettings = $this->getCompanySettings($loggedUserId);

        return view('shift-management', compact(
            'users', 
            'shifts', 
            'events', 
            'accessLevel', 
            'companySettings'
        ));
    }

    /**
     * Assign multiple shifts to users
     */
    public function assignShifts(Request $request)
    {
        $loggedUserId = Auth::id();
        
        // First try to find in stafify_users table
        $user = StafifyUser::find($loggedUserId);
        
        // If not found in stafify_users, try the main users table
        if (!$user) {
            $mainUser = \App\Models\User::find($loggedUserId);
            if (!$mainUser || $mainUser->access_level != 2) {
                return redirect()->route('shift-management')
                    ->with('error', 'You don\'t have permission to assign shifts.');
            }
        } else if ($user->access_level != 2) {
            return redirect()->route('shift-management')
                ->with('error', 'You don\'t have permission to assign shifts.');
        }

        $validator = Validator::make($request->all(), [
            'selected_users' => 'required|array|min:1',
            'selected_users.*' => 'exists:stafify_users,user_id',
            'assign_day' => 'required|array|min:1',
            'start_time' => 'required|array',
            'end_time' => 'required|array',
            'include_break' => 'nullable|array',
            'break_duration' => 'nullable|array',
            'break_custom_minutes' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->route('shift-management')
                ->withErrors($validator)
                ->with('error', 'Please check your input and try again.');
        }

        $userIds = $request->selected_users;
        $assignDays = array_keys($request->assign_day);
        $startTimes = $request->start_time;
        $endTimes = $request->end_time;
        $locations = $request->location ?? [];
        $notes = $request->notes ?? [];
        $includeBreaks = $request->include_break ?? [];
        $breakDurations = $request->break_duration ?? [];
        $breakCustomMinutes = $request->break_custom_minutes ?? [];

        // Filter days to only include those that are checked
        $validDays = [];
        foreach ($assignDays as $date) {
            if (isset($startTimes[$date]) && !empty($startTimes[$date]) && 
                isset($endTimes[$date]) && !empty($endTimes[$date])) {
                $validDays[] = $date;
            }
        }

        if (empty($validDays)) {
            return redirect()->route('shift-management')
                ->with('error', 'No valid shifts found. Please check start and end times.');
        }

        $result = $this->assignMultipleShifts(
            $userIds, 
            $loggedUserId, 
            $validDays, 
            $startTimes, 
            $endTimes, 
            $locations, 
            $notes,
            $includeBreaks,
            $breakDurations,
            $breakCustomMinutes
        );

        if ($result['success']) {
            return redirect()->route('shift-management')
                ->with('success', "Successfully assigned {$result['assigned']} shifts. {$result['skipped']} shifts were skipped (already exist).");
        } else {
            return redirect()->route('shift-management')
                ->with('error', 'Error assigning shifts. Please try again.');
        }
    }

    /**
     * Add a new event
     */
    public function addEvent(Request $request)
    {
        $loggedUserId = Auth::id();
        
        // First try to find in stafify_users table
        $user = StafifyUser::find($loggedUserId);
        
        // If not found in stafify_users, try the main users table
        if (!$user) {
            $mainUser = \App\Models\User::find($loggedUserId);
            if (!$mainUser || $mainUser->access_level != 2) {
                return redirect()->route('shift-management')
                    ->with('error', 'You don\'t have permission to add events.');
            }
        } else if ($user->access_level != 2) {
            return redirect()->route('shift-management')
                ->with('error', 'You don\'t have permission to add events.');
        }

        $validator = Validator::make($request->all(), [
            'event_title' => 'required|string|max:255',
            'event_date' => 'required|date',
            'event_start_time' => 'required|date_format:H:i',
            'event_end_time' => 'required|date_format:H:i|after:event_start_time',
            'event_location' => 'nullable|string|max:100',
            'event_type' => 'required|string|in:meeting,training,holiday,announcement,other',
            'event_visibility' => 'required|string|in:all,management',
            'event_description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('shift-management')
                ->withErrors($validator)
                ->with('error', 'Please check your input and try again.');
        }

        try {
            StafifyEvent::create([
                'created_by' => $loggedUserId,
                'event_title' => $request->event_title,
                'event_date' => $request->event_date,
                'start_time' => $request->event_start_time,
                'end_time' => $request->event_end_time,
                'event_location' => $request->event_location,
                'event_type' => $request->event_type,
                'event_visibility' => $request->event_visibility,
                'event_description' => $request->event_description,
            ]);

            return redirect()->route('shift-management')
                ->with('success', 'Event added successfully.');
        } catch (\Exception $e) {
            return redirect()->route('shift-management')
                ->with('error', 'Error adding event. Please try again.');
        }
    }

    /**
     * Save company settings
     */
    public function saveSettings(Request $request)
    {
        $loggedUserId = Auth::id();
        
        // First try to find in stafify_users table
        $user = StafifyUser::find($loggedUserId);
        
        // If not found in stafify_users, try the main users table
        if (!$user) {
            $mainUser = \App\Models\User::find($loggedUserId);
            if (!$mainUser || $mainUser->access_level != 2) {
                return redirect()->route('shift-management')
                    ->with('error', 'You don\'t have permission to modify company settings.');
            }
        } else if ($user->access_level != 2) {
            return redirect()->route('shift-management')
                ->with('error', 'You don\'t have permission to modify company settings.');
        }

        $validator = Validator::make($request->all(), [
            'early_clock_in_minutes' => 'required|integer|min:0|max:60',
            'on_time_late_minutes' => 'required|integer|min:0|max:30',
        ]);

        if ($validator->fails()) {
            return redirect()->route('shift-management')
                ->withErrors($validator)
                ->with('error', 'Invalid time values. Please check your inputs.');
        }

        try {
            $this->saveCompanySettings($loggedUserId, [
                'early_clock_in_minutes' => $request->early_clock_in_minutes,
                'on_time_late_minutes' => $request->on_time_late_minutes,
            ]);

            return redirect()->route('shift-management')
                ->with('success', 'Late clock-in settings updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('shift-management')
                ->with('error', 'Error updating settings. Please try again.');
        }
    }

    /**
     * Get company shifts based on access level
     */
    private function getCompanyShifts($loggedUserId, $accessLevel)
    {
        $query = StafifyShift::with(['user', 'assignedBy'])
            ->select('stafify_shifts.*')
            ->join('stafify_users as u', 'stafify_shifts.user_id', '=', 'u.user_id')
            ->join('stafify_users as a', 'stafify_shifts.assigned_by', '=', 'a.user_id');

        // Filter by user ID if access level is 3 (employee)
        if ($accessLevel == 3) {
            $query->where('stafify_shifts.user_id', $loggedUserId);
        }

        return $query->orderBy('shift_date', 'desc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($shift) {
                return [
                    'shift_id' => $shift->shift_id,
                    'user_id' => $shift->user_id,
                    'assigned_by' => $shift->assigned_by,
                    'shift_date' => $shift->shift_date->format('Y-m-d'),
                    'start_time' => $shift->start_time,
                    'end_time' => $shift->end_time,
                    'shift_type' => $shift->shift_type,
                    'location' => $shift->location,
                    'notes' => $shift->notes,
                    'include_break' => $shift->include_break,
                    'break_duration_minutes' => $shift->break_duration_minutes,
                    'employee_name' => $shift->user->full_name ?? 'Unknown',
                    'assigned_by_name' => $shift->assignedBy->full_name ?? 'Unknown',
                    'created_at' => $shift->created_at,
                ];
            });
    }

    /**
     * Get company events based on access level
     */
    private function getCompanyEvents($loggedUserId, $accessLevel)
    {
        $query = StafifyEvent::with('createdBy');

        // Filter by visibility if access level is 3 (employee)
        if ($accessLevel == 3) {
            $query->where('event_visibility', 'all');
        }

        return $query->orderBy('event_date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get()
            ->map(function ($event) {
                return [
                    'event_id' => $event->event_id,
                    'created_by' => $event->created_by,
                    'event_title' => $event->event_title,
                    'event_date' => $event->event_date->format('Y-m-d'),
                    'start_time' => $event->start_time,
                    'end_time' => $event->end_time,
                    'event_location' => $event->event_location,
                    'event_type' => $event->event_type,
                    'event_visibility' => $event->event_visibility,
                    'event_description' => $event->event_description,
                    'created_by_name' => $event->createdBy->full_name ?? 'Unknown',
                    'created_at' => $event->created_at,
                ];
            });
    }

    /**
     * Get company settings
     */
    private function getCompanySettings($loggedUserId)
    {
        // First try to find in stafify_users table
        $user = StafifyUser::find($loggedUserId);
        
        // If not found in stafify_users, try the main users table
        if (!$user) {
            $mainUser = \App\Models\User::find($loggedUserId);
            if (!$mainUser) {
                return [
                    'early_clock_in_minutes' => 15,
                    'on_time_late_minutes' => 5,
                ];
            }
            $company = $mainUser->company ?? 'Default Company';
        } else {
            $company = $user->company ?? 'Default Company';
        }

        $settings = StafifyCompanySetting::where('company', $company)->get();
        
        $result = [
            'early_clock_in_minutes' => 15,
            'on_time_late_minutes' => 5,
        ];

        foreach ($settings as $setting) {
            $result[$setting->setting_key] = $setting->setting_value;
        }

        return $result;
    }

    /**
     * Save company settings
     */
    private function saveCompanySettings($loggedUserId, $settings)
    {
        // First try to find in stafify_users table
        $user = StafifyUser::find($loggedUserId);
        
        // If not found in stafify_users, try the main users table
        if (!$user) {
            $mainUser = \App\Models\User::find($loggedUserId);
            if (!$mainUser) {
                throw new \Exception('User not found');
            }
            $company = $mainUser->company ?? 'Default Company';
        } else {
            $company = $user->company ?? 'Default Company';
        }

        foreach ($settings as $key => $value) {
            StafifyCompanySetting::updateOrCreate(
                [
                    'company' => $company,
                    'setting_key' => $key,
                ],
                [
                    'setting_value' => $value,
                    'updated_by' => $loggedUserId,
                ]
            );
        }
    }

    /**
     * Assign multiple shifts
     */
    private function assignMultipleShifts($userIds, $assignedBy, $dates, $startTimes, $endTimes, $locations, $notes, $includeBreaks = [], $breakDurations = [], $breakCustomMinutes = [])
    {
        $assigned = 0;
        $skipped = 0;

        try {
            DB::beginTransaction();

            foreach ($userIds as $userId) {
                foreach ($dates as $date) {
                    // Check if this shift already exists
                    $existingShift = StafifyShift::where('user_id', $userId)
                        ->where('shift_date', $date)
                        ->where('start_time', $startTimes[$date])
                        ->where('end_time', $endTimes[$date])
                        ->first();

                    if ($existingShift) {
                        $skipped++;
                        continue;
                    }

                    // Determine shift type based on time
                    $startHour = (int)substr($startTimes[$date], 0, 2);
                    if ($startHour >= 5 && $startHour < 12) {
                        $shiftType = 'Morning';
                    } else if ($startHour >= 12 && $startHour < 18) {
                        $shiftType = 'Afternoon';
                    } else {
                        $shiftType = 'Night';
                    }

                    // Get location and notes for this date
                    $location = $locations[$date] ?? '';
                    $note = $notes[$date] ?? '';
                    
                    // Handle break information
                    $includeBreak = isset($includeBreaks[$date]) && $includeBreaks[$date] == 'on';
                    $breakDurationMinutes = null;
                    
                    if ($includeBreak) {
                        $breakDuration = $breakDurations[$date] ?? null;
                        
                        if ($breakDuration === 'custom') {
                            // Use custom minutes if provided
                            $breakDurationMinutes = isset($breakCustomMinutes[$date]) && !empty($breakCustomMinutes[$date])
                                ? (int)$breakCustomMinutes[$date]
                                : null;
                        } else if ($breakDuration) {
                            // Use predefined duration (15 or 60 minutes)
                            $breakDurationMinutes = (int)$breakDuration;
                        }
                    }

                    StafifyShift::create([
                        'user_id' => $userId,
                        'assigned_by' => $assignedBy,
                        'shift_date' => $date,
                        'start_time' => $startTimes[$date],
                        'end_time' => $endTimes[$date],
                        'shift_type' => $shiftType,
                        'location' => $location,
                        'notes' => $note,
                        'include_break' => $includeBreak,
                        'break_duration_minutes' => $breakDurationMinutes,
                    ]);

                    $assigned++;
                }
            }

            DB::commit();

            return [
                'success' => true,
                'assigned' => $assigned,
                'skipped' => $skipped,
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'assigned' => $assigned,
                'skipped' => $skipped,
            ];
        }
    }
}
