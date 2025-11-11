<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class WorkforceRecordsController extends Controller
{
    public function index()
    {
        // Get all users with their data
        $users = User::where('is_archived', false)
            ->orderBy('full_name')
            ->get();

        // Get unique departments for filter
        $departments = User::where('is_archived', false)
            ->whereNotNull('user_dept')
            ->distinct()
            ->pluck('user_dept')
            ->sort()
            ->values();

        return view('workforce-records', compact('users', 'departments'));
    }

    public function getUserRates(Request $request)
    {
        $userId = $request->get('user_id');
        
        try {
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Get user rates from stafify_user_rates table
            $ratesData = DB::table('stafify_user_rates')
                ->where('user_id', $userId)
                ->first();

            if (!$ratesData) {
                // Return default values if no rates found
                $rates = [
                    'hourly_rate' => '0.00',
                    'daily_rate' => '0.00',
                    'monthly_rate' => '0.00'
                ];
            } else {
                $rates = [
                    'hourly_rate' => $ratesData->hourly_rate ?? '0.00',
                    'daily_rate' => $ratesData->daily_rate ?? '0.00',
                    'monthly_rate' => $ratesData->monthly_rate ?? '0.00'
                ];
            }

            return response()->json([
                'success' => true,
                'rates' => $rates
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user rates: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateUserRates(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'hourly_rate' => 'required|numeric|min:0',
            'daily_rate' => 'required|numeric|min:0',
            'monthly_rate' => 'required|numeric|min:0'
        ]);

        try {
            // Update or insert rates in stafify_user_rates table
            $existing = DB::table('stafify_user_rates')->where('user_id', $request->user_id)->first();
            
            if ($existing) {
                DB::table('stafify_user_rates')
                    ->where('user_id', $request->user_id)
                    ->update([
                        'hourly_rate' => $request->hourly_rate,
                        'daily_rate' => $request->daily_rate,
                        'monthly_rate' => $request->monthly_rate,
                        'updated_at' => now()
                    ]);
            } else {
                DB::table('stafify_user_rates')->insert([
                    'user_id' => $request->user_id,
                    'hourly_rate' => $request->hourly_rate,
                    'daily_rate' => $request->daily_rate,
                    'monthly_rate' => $request->monthly_rate,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'User rates updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user rates: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUserSettings(Request $request)
    {
        $userId = $request->get('user_id');
        
        try {
            $user = User::find($userId);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            $settings = [
                'engagement_status' => $user->engagement_status ?? 'full_time',
                'user_type' => $user->user_type ?? 'employee',
                'user_status' => $user->user_status ?? 'active',
                'sil_status' => $user->sil_status ? '1' : '0',
                'wage_type' => $user->wage_type ?? 'non_mwe'
            ];

            return response()->json([
                'success' => true,
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user settings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateUserSettings(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'engagement_status' => 'required|in:full_time,part_time',
            'user_type' => 'required|in:employee,isp',
            'user_status' => 'required|in:active,awol,blacklisted,resigned,transferred,disengaged,engaged',
            'sil_status' => 'required|in:0,1',
            'wage_type' => 'required'
        ]);

        try {
            $user = User::find($request->user_id);
            $user->update([
                'engagement_status' => $request->engagement_status,
                'user_type' => $request->user_type,
                'user_status' => $request->user_status,
                'sil_status' => $request->sil_status === '1',
                'wage_type' => $request->wage_type
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User settings updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user settings: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUserFiles(Request $request)
    {
        $userEmail = $request->get('email');
        
        try {
            $user = User::where('user_email', $userEmail)->first();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Get user files (assuming these are stored in user table or separate files table)
            $files = [
                'resume' => $user->resume_file ?? '',
                'nbi' => $user->nbi_file ?? '',
                'license' => $user->license_file ?? '',
                'health' => $user->health_file ?? ''
            ];

            return response()->json([
                'success' => true,
                'files' => $files
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching user files: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFringeBenefits(Request $request)
    {
        $userId = $request->get('user_id');
        
        try {
            // Assuming fringe benefits are stored in a separate table or user table
            $benefits = DB::table('stafify_fringe_benefits')
                ->where('user_id', $userId)
                ->first();

            if (!$benefits) {
                // Return default values if no benefits found
                $benefits = (object) [
                    'hazard_pay' => '0.00',
                    'fixed_representation_allowance' => '0.00',
                    'fixed_transportation_allowance' => '0.00',
                    'fixed_housing_allowance' => '0.00',
                    'vehicle_allowance' => '0.00',
                    'educational_assistance' => '0.00',
                    'medical_assistance' => '0.00',
                    'insurance' => '0.00',
                    'membership' => '0.00',
                    'household_personnel' => '0.00',
                    'vacation_expense' => '0.00',
                    'travel_expense' => '0.00',
                    'commissions' => '0.00',
                    'profit_sharing' => '0.00',
                    'fees' => '0.00',
                    'total_taxable_13' => '0.00',
                    'other_taxable' => '0.00',
                    'total_taxable_benefits' => '0.00'
                ];
            }

            return response()->json([
                'success' => true,
                'benefits' => $benefits
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching fringe benefits: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateFringeBenefits(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'hazard_pay' => 'numeric|min:0',
            'fixed_representation_allowance' => 'numeric|min:0',
            'fixed_transportation_allowance' => 'numeric|min:0',
            'fixed_housing_allowance' => 'numeric|min:0',
            'vehicle_allowance' => 'numeric|min:0',
            'educational_assistance' => 'numeric|min:0',
            'medical_assistance' => 'numeric|min:0',
            'insurance' => 'numeric|min:0',
            'membership' => 'numeric|min:0',
            'household_personnel' => 'numeric|min:0',
            'vacation_expense' => 'numeric|min:0',
            'travel_expense' => 'numeric|min:0',
            'commissions' => 'numeric|min:0',
            'profit_sharing' => 'numeric|min:0',
            'fees' => 'numeric|min:0',
            'total_taxable_13' => 'numeric|min:0',
            'other_taxable' => 'numeric|min:0',
            'total_taxable_benefits' => 'numeric|min:0'
        ]);

        try {
            $existing = DB::table('stafify_fringe_benefits')->where('user_id', $request->user_id)->first();
            $data = $request->except(['_token', 'user_id']);
            
            if ($existing) {
                $data['updated_at'] = now();
                DB::table('stafify_fringe_benefits')
                    ->where('user_id', $request->user_id)
                    ->update($data);
            } else {
                $data['user_id'] = $request->user_id;
                $data['created_at'] = now();
                $data['updated_at'] = now();
                DB::table('stafify_fringe_benefits')->insert($data);
            }

            return response()->json([
                'success' => true,
                'message' => 'Fringe benefits updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating fringe benefits: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getDeMinimisBenefits(Request $request)
    {
        $userId = $request->get('user_id');
        
        try {
            $benefits = DB::table('stafify_deminimis_benefits')
                ->where('user_id', $userId)
                ->first();

            if (!$benefits) {
                // Return default values if no benefits found
                $benefits = (object) [
                    'rice_subsidy' => '0.00',
                    'meal_allowance' => '0.00',
                    'uniform_clothing' => '0.00',
                    'laundry_allowance' => '0.00',
                    'medical_allowance' => '0.00',
                    'collective_bargaining_agreement' => '0.00',
                    'total_non_taxable_13' => '0.00',
                    'service_incentive_leave' => '0.00',
                    'paid_time_off' => '0.00',
                    'other_allowances' => '0.00',
                    'total_non_taxable_benefits' => '0.00'
                ];
            }

            return response()->json([
                'success' => true,
                'benefits' => $benefits
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching de minimis benefits: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateDeMinimisBenefits(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'rice_subsidy' => 'numeric|min:0',
            'meal_allowance' => 'numeric|min:0',
            'uniform_clothing' => 'numeric|min:0',
            'laundry_allowance' => 'numeric|min:0',
            'medical_allowance' => 'numeric|min:0',
            'collective_bargaining_agreement' => 'numeric|min:0',
            'total_non_taxable_13' => 'numeric|min:0',
            'service_incentive_leave' => 'numeric|min:0',
            'paid_time_off' => 'numeric|min:0',
            'other_allowances' => 'numeric|min:0',
            'total_non_taxable_benefits' => 'numeric|min:0'
        ]);

        try {
            $existing = DB::table('stafify_deminimis_benefits')->where('user_id', $request->user_id)->first();
            $data = $request->except(['_token', 'user_id']);
            
            if ($existing) {
                $data['updated_at'] = now();
                DB::table('stafify_deminimis_benefits')
                    ->where('user_id', $request->user_id)
                    ->update($data);
            } else {
                $data['user_id'] = $request->user_id;
                $data['created_at'] = now();
                $data['updated_at'] = now();
                DB::table('stafify_deminimis_benefits')->insert($data);
            }

            return response()->json([
                'success' => true,
                'message' => 'De minimis benefits updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating de minimis benefits: ' . $e->getMessage()
            ], 500);
        }
    }

    public function saveEvaluation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,user_id',
            'evaluation_date' => 'required|date',
            'evaluator_name' => 'required|string|max:255',
            'evaluation_type' => 'required|string|max:100',
            'remarks' => 'nullable|string',
            'overall_score' => 'required|integer|min:0|max:100',
            'job_knowledge' => 'required|integer|min:0|max:5',
            'productivity' => 'required|integer|min:0|max:5',
            'work_quality' => 'required|integer|min:0|max:5',
            'technical_skills' => 'required|integer|min:0|max:5',
            'work_consistency' => 'required|integer|min:0|max:5',
            'enthusiasm' => 'required|integer|min:0|max:5',
            'cooperation' => 'required|integer|min:0|max:5',
            'attitude' => 'required|integer|min:0|max:5',
            'initiative' => 'required|integer|min:0|max:5',
            'work_relations' => 'required|integer|min:0|max:5',
            'creativity' => 'required|integer|min:0|max:5',
            'punctuality' => 'required|integer|min:0|max:5',
            'attendance' => 'required|integer|min:0|max:5',
            'dependability' => 'required|integer|min:0|max:5',
            'written_comm' => 'required|integer|min:0|max:5',
            'verbal_comm' => 'required|integer|min:0|max:5',
            'appearance' => 'required|integer|min:0|max:5',
            'uniform' => 'required|integer|min:0|max:5',
            'personal_hygiene' => 'required|integer|min:0|max:5',
            'tidiness' => 'required|integer|min:0|max:5',
        ]);

        try {
            DB::table('performance_evaluations')->insert([
                'user_id' => $request->user_id,
                'evaluation_date' => $request->evaluation_date,
                'evaluator_name' => $request->evaluator_name,
                'evaluation_type' => $request->evaluation_type,
                'remarks' => $request->remarks,
                'overall_score' => $request->overall_score,
                'job_knowledge' => $request->job_knowledge,
                'productivity' => $request->productivity,
                'work_quality' => $request->work_quality,
                'technical_skills' => $request->technical_skills,
                'work_consistency' => $request->work_consistency,
                'enthusiasm' => $request->enthusiasm,
                'cooperation' => $request->cooperation,
                'attitude' => $request->attitude,
                'initiative' => $request->initiative,
                'work_relations' => $request->work_relations,
                'creativity' => $request->creativity,
                'punctuality' => $request->punctuality,
                'attendance' => $request->attendance,
                'dependability' => $request->dependability,
                'written_comm' => $request->written_comm,
                'verbal_comm' => $request->verbal_comm,
                'appearance' => $request->appearance,
                'uniform' => $request->uniform,
                'personal_hygiene' => $request->personal_hygiene,
                'tidiness' => $request->tidiness,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Evaluation saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving evaluation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateEvaluation(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:performance_evaluations,id',
            'user_id' => 'required|exists:users,user_id',
            'evaluation_date' => 'required|date',
            'evaluator_name' => 'required|string|max:255',
            'evaluation_type' => 'required|string|max:100',
            'remarks' => 'nullable|string',
            'overall_score' => 'required|integer|min:0|max:100',
            'job_knowledge' => 'required|integer|min:0|max:5',
            'productivity' => 'required|integer|min:0|max:5',
            'work_quality' => 'required|integer|min:0|max:5',
            'technical_skills' => 'required|integer|min:0|max:5',
            'work_consistency' => 'required|integer|min:0|max:5',
            'enthusiasm' => 'required|integer|min:0|max:5',
            'cooperation' => 'required|integer|min:0|max:5',
            'attitude' => 'required|integer|min:0|max:5',
            'initiative' => 'required|integer|min:0|max:5',
            'work_relations' => 'required|integer|min:0|max:5',
            'creativity' => 'required|integer|min:0|max:5',
            'punctuality' => 'required|integer|min:0|max:5',
            'attendance' => 'required|integer|min:0|max:5',
            'dependability' => 'required|integer|min:0|max:5',
            'written_comm' => 'required|integer|min:0|max:5',
            'verbal_comm' => 'required|integer|min:0|max:5',
            'appearance' => 'required|integer|min:0|max:5',
            'uniform' => 'required|integer|min:0|max:5',
            'personal_hygiene' => 'required|integer|min:0|max:5',
            'tidiness' => 'required|integer|min:0|max:5',
        ]);

        try {
            DB::table('performance_evaluations')
                ->where('id', $request->evaluation_id)
                ->update([
                    'user_id' => $request->user_id,
                    'evaluation_date' => $request->evaluation_date,
                    'evaluator_name' => $request->evaluator_name,
                    'evaluation_type' => $request->evaluation_type,
                    'remarks' => $request->remarks,
                    'overall_score' => $request->overall_score,
                    'job_knowledge' => $request->job_knowledge,
                    'productivity' => $request->productivity,
                    'work_quality' => $request->work_quality,
                    'technical_skills' => $request->technical_skills,
                    'work_consistency' => $request->work_consistency,
                    'enthusiasm' => $request->enthusiasm,
                    'cooperation' => $request->cooperation,
                    'attitude' => $request->attitude,
                    'initiative' => $request->initiative,
                    'work_relations' => $request->work_relations,
                    'creativity' => $request->creativity,
                    'punctuality' => $request->punctuality,
                    'attendance' => $request->attendance,
                    'dependability' => $request->dependability,
                    'written_comm' => $request->written_comm,
                    'verbal_comm' => $request->verbal_comm,
                    'appearance' => $request->appearance,
                    'uniform' => $request->uniform,
                    'personal_hygiene' => $request->personal_hygiene,
                    'tidiness' => $request->tidiness,
                    'updated_at' => now(),
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Evaluation updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating evaluation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getEvaluation(Request $request)
    {
        $evaluationId = $request->get('evaluation_id');
        
        try {
            $evaluation = DB::table('performance_evaluations')
                ->where('id', $evaluationId)
                ->first();

            if (!$evaluation) {
                return response()->json([
                    'success' => false,
                    'message' => 'Evaluation not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'evaluation' => $evaluation
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching evaluation: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteEvaluation(Request $request)
    {
        $request->validate([
            'evaluation_id' => 'required|exists:performance_evaluations,id'
        ]);

        try {
            DB::table('performance_evaluations')
                ->where('id', $request->evaluation_id)
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Evaluation deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting evaluation: ' . $e->getMessage()
            ], 500);
        }
    }
}
