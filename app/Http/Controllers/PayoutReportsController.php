<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\StafifyPayroll;
use App\Models\PayrollSettings;
use App\Models\User;
use App\Models\StafifyTimeTracking;
use App\Models\StafifyOvertime;
use Carbon\Carbon;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Sheets;
use Google_Service_Drive_DriveFile;
use Google_Service_Sheets_ValueRange;
use Google_Service_Sheets_BatchUpdateValuesRequest;
use Exception;

class PayoutReportsController extends Controller
{
    private $driveService;
    private $sheetService;
    
    public function __construct()
    {
        // Initialize Google Services
        $client = new Google_Client();
        $client->setAuthConfig(base_path('stafify-5d43b0bf4f7b.json'));
        $client->addScope(Google_Service_Drive::DRIVE);
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);
    
        try {
            $this->driveService = new Google_Service_Drive($client);
            $this->sheetService = new Google_Service_Sheets($client);
        } catch (Exception $e) {
            // Handle authentication failure gracefully
            logger("Google API authentication failed: " . $e->getMessage());
        }        
    }

    public function index()
    {
        return view('payout-reports');
    }

    public function api(Request $request): JsonResponse
    {
        $method = $request->method();
        $path = $request->query('path', '');
        $id = $request->query('id', '');
        $name = $request->query('name', '');
        $folderID = $request->query('folderID', '');
        $weeks = $request->query('weeks', '');
        $category = $request->query('category', '');
        $templateId = $request->query('templateId', '');
        $data = $request->all();

        switch ($method) {
            case 'GET':
                switch ($path) {
                    case 'initial':
                        $categories = $this->getCategories();
                        return $this->responseHandler(true, "Data fetched successfully", [
                            'categories' => $categories,
                            'payrolls' => $this->getPayrolls($categories[0]->id ?? null),
                            'settings' => $this->getPayrollSettings(),
                            'templates' => $this->getTemplates()
                        ]);

                    case 'payrolls':
                        return $this->responseHandler(true, "Payrolls fetched successfully", $this->getPayrolls($id));

                    case 'categories':
                        return $this->responseHandler(true, "Categories fetched successfully", $this->getCategories());

                    case 'templates':
                        return $this->responseHandler(true, "Templates fetched successfully", $this->getTemplates());

                    default:
                        return $this->responseHandler(false, "URL path can't be found");   
                }

            case 'POST':
                switch ($path) {
                    case 'payrolls/create':
                        return $this->duplicateTemplate($name, $folderID, $weeks, $templateId);

                    case 'payrolls/rename':
                        return $this->renamePayroll($id, $name);
                        
                    case 'payrolls/delete':
                        return $this->deletePayroll($id);

                    case 'payrolls/sync':
                        return $this->updateUserData($id, $category);

                    case 'settings/update':
                        return $this->updatePayrollSettings($id, $data);

                    default:
                        return $this->responseHandler(false, "URL path can't be found");
                }

            default:
                return $this->responseHandler(false, "Invalid API call");
        }
    }

    private function responseHandler(bool $success, string $message, $data = null): JsonResponse
    {
        if($data) $data = json_encode($data);
        return response()->json([
            "success" => $success,
            "message" => $message,
            "data" => $data
        ]);
    }

    private function getCategories()
    {
        return StafifyPayroll::all();
    }

    private function getPayrolls($category_id)
    {
        if (!$this->driveService || !$category_id) {
            return [];
        }

        try {
            $folders = $this->getFolderContents($category_id);
            return array_map(function ($folder) {
                return [
                    "id" => $folder->getId(),
                    "name" => $folder->getName(),
                    "url" => $folder->getWebViewLink(),
                    "createdTime" => (new \DateTime($folder->getCreatedTime()))->format('M. j, Y'),
                    "modifiedTime" => (new \DateTime($folder->getModifiedTime()))->format('M. j, Y'),
                    "ownerName" => $folder->getOwners()[0]->getDisplayName() ?? null,
                    "ownerEmail" => $folder->getOwners()[0]->getEmailAddress() ?? null,
                ];               
            }, $folders);
        } catch (Exception $e) {
            logger("Error fetching payrolls: " . $e->getMessage());
            return [];
        }
    }

    private function getFolderContents($folderId)
    {
        $query = "'$folderId' in parents and trashed = false";
        $response = $this->driveService->files->listFiles([
            'q' => $query,
            'fields' => 'files(id, name, mimeType, webViewLink, createdTime, modifiedTime, owners(displayName, emailAddress))'
        ]);
    
        return $response->getFiles();
    }

    private function renamePayroll($id, $newName)
    {
        if (!$this->driveService) {
            return $this->responseHandler(false, "Google Drive service not available");
        }

        try {
            $fileMetadata = new Google_Service_Drive_DriveFile([
                'name' => $newName
            ]);

            $updatedFile = $this->driveService->files->update($id, $fileMetadata, [
                'fields' => 'id, name'
            ]);

            return $this->responseHandler(true, '"' . $updatedFile->getName() . "\" payroll renamed successfully!");

        } catch (Exception $e) {
            return $this->responseHandler(false, 'Error renaming file: ' . $e->getMessage());
        }
    }

    private function deletePayroll($id)
    {
        if (!$this->driveService) {
            return $this->responseHandler(false, "Google Drive service not available");
        }

        try {
            $this->driveService->files->update($id, new Google_Service_Drive_DriveFile([
                'trashed' => true
            ]));

            return $this->responseHandler(true, 'Payroll deleted successfully');
        } catch (Exception $e) {
            $error = json_decode($e->getMessage());
            return $this->responseHandler(false, $error->error->message ?? $e->getMessage());
        }
    }

    private function getTemplates()
    {
        if (!$this->driveService) {
            return [];
        }

        // Template folder ID from Google Drive
        $templateFolderId = '1KPmwQf8aUKseenURHRNHvYCoZcC2gpFu';

        try {
            $files = $this->getFolderContents($templateFolderId);
            return array_map(function ($file) {
                return [
                    'id' => $file->getId(),
                    'name' => $file->getName(),
                    'url' => $file->getWebViewLink()
                ];
            }, $files);
        } catch (Exception $e) {
            logger("Error fetching templates: " . $e->getMessage());
            return [];
        }
    }

    private function duplicateTemplate($newFileName, $folderId, $count, $templateId = null)
    {
        if (!$this->driveService) {
            return $this->responseHandler(false, "Google Drive service not available");
        }

        $category = StafifyPayroll::find($folderId);

        if (!$category) {
            return $this->responseHandler(false, "Invalid request");
        }

        // Use provided template_id or fall back to category's template_id
        $sourceTemplateId = $templateId ?: $category->template_id;

        $fileMetadata = new Google_Service_Drive_DriveFile([
            'name' => $newFileName,
            'parents' => [$folderId]
        ]);

        try {
            $copiedFile = $this->driveService->files->copy(
                $sourceTemplateId, 
                $fileMetadata,
                ['fields' => 'id,name,webViewLink']
            );

            $spreadsheetId = $copiedFile->getId();
            
            // Determine employee type from template name or category
            $empType = $category->name;
            if ($templateId) {
                // Try to determine from template name
                $templates = $this->getTemplates();
                foreach ($templates as $template) {
                    if ($template['id'] === $templateId) {
                        $empType = $template['name'];
                        break;
                    }
                }
            }
            
            $this->updateUserData($spreadsheetId, $empType);

            return $this->responseHandler(true, "Payroll created successfully", [
                'id' => $copiedFile->getId(),
                'name' => $copiedFile->getName(),
                'url' => $copiedFile->getWebViewLink()
            ]);
        } catch (Exception $e) {
            return $this->responseHandler(false, "Failed to create payroll: " . $e->getMessage());
        }
    }

    private function updateUserData($fileID, $empType)
    {
        if (!$this->sheetService) {
            return $this->responseHandler(false, "Google Sheets service not available");
        }

        try {
            // Log the received category for debugging
            logger("Sync called with empType: " . $empType);
            
            // Normalize category name (handle case variations)
            $normalizedEmpType = ucfirst(strtolower(trim($empType)));
        
            // Build query based on employee type with case-insensitive matching
            $query = User::where('is_archived', 0); // Exclude archived users
            
        if ($normalizedEmpType === 'Hybrid') {
            // Hybrid includes both ISP and Employee types
                $users = $query->where(function($q) {
                    $q->whereRaw('LOWER(user_type) = ?', ['isp'])
                      ->orWhereRaw('LOWER(user_type) = ?', ['employee']);
                })->get();
            } elseif ($normalizedEmpType === 'Isp' || $normalizedEmpType === 'ISP') {
                // ISP category - try multiple case variations
                $users = $query->where(function($q) {
                    $q->whereRaw('LOWER(user_type) = ?', ['isp'])
                      ->orWhere('user_type', 'ISP')
                      ->orWhere('user_type', 'isp');
                })->get();
            } elseif ($normalizedEmpType === 'Ee' || $normalizedEmpType === 'EE') {
                // EE (Employee) category - try multiple case variations
                $users = $query->where(function($q) {
                    $q->whereRaw('LOWER(user_type) = ?', ['employee'])
                      ->orWhere('user_type', 'Employee')
                      ->orWhere('user_type', 'employee')
                      ->orWhere('user_type', 'EE');
                })->get();
        } else {
                // Fallback: try case-insensitive match
                $users = $query->whereRaw('LOWER(user_type) = ?', [strtolower($empType)])->get();
            }

            // Log the query result for debugging
            logger("Found " . $users->count() . " users for category: " . $empType);
            
            // If no users found, try to get all users as fallback with warning
            $usedFallback = false;
            if ($users->isEmpty()) {
                // Try to find all user types in database for debugging
                $allUserTypes = User::distinct()->whereNotNull('user_type')->pluck('user_type')->toArray();
                logger("Available user_types in database: " . implode(', ', $allUserTypes));
                
                // Fallback: if category doesn't match, use all active users (with warning in log)
                $allUsers = User::where('is_archived', 0)->get();
                
                if ($allUsers->isEmpty()) {
                    return $this->responseHandler(false, "No active users found in the system.");
                }
                
                logger("WARNING: Category '{$empType}' doesn't match any users. Using all active users as fallback. Please update user_type values to match categories (ISP, Employee, etc.)");
                $users = $allUsers;
                $usedFallback = true;
                
                // You can uncomment the line below to enforce strict category matching instead of fallback:
                // return $this->responseHandler(false, "No users found for category '{$empType}'. Available types: " . implode(', ', $allUserTypes));
            }

            // Get spreadsheet metadata to find the first sheet
            $spreadsheet = $this->sheetService->spreadsheets->get($fileID);
            $sheets = $spreadsheet->getSheets();
            
            if (empty($sheets)) {
                return $this->responseHandler(false, "No sheets found in the spreadsheet");
        }

            $sheetTitle = $sheets[0]->getProperties()->getTitle();
            
            // Get date range from spreadsheet (if available) or use current month
            $dateRange = $this->getPayrollDateRange($fileID, $sheetTitle);
            
            // Read existing data to find starting row and map columns
            $startingRow = $this->findDataStartingRow($fileID, $sheetTitle);
            
            // Prepare batch update data
            $updateData = [];
            
            foreach ($users as $index => $user) {
                $userData = $this->calculateUserPayrollData($user, $dateRange);
                $rowNumber = $startingRow + $index;
                
                // Update specific cells for each user
                $cellUpdates = $this->buildCellUpdates($user, $userData, $rowNumber, $sheetTitle);
                $updateData = array_merge($updateData, $cellUpdates);
            }

            // Perform batch update
            if (!empty($updateData)) {
                // Convert updateData to the correct format
                $valueRanges = array_map(function($item) {
                    return new Google_Service_Sheets_ValueRange([
                        'range' => $item['range'],
                        'values' => [[$item['value']]]
                    ]);
                }, $updateData);

                $body = new Google_Service_Sheets_BatchUpdateValuesRequest([
                    'valueInputOption' => 'USER_ENTERED',
                    'data' => $valueRanges
                ]);
                
                $this->sheetService->spreadsheets_values->batchUpdate($fileID, $body);
            }

            $message = "Payroll synced successfully! Updated " . count($users) . " employees.";
            if ($usedFallback) {
                $message .= " Note: Category '{$empType}' didn't match any users, so all active users were synced. Please update user_type values in user records to match categories (ISP, Employee, etc.).";
            }
            
            return $this->responseHandler(true, $message);
            
        } catch (Exception $e) {
            logger("Error syncing payroll: " . $e->getMessage());
            return $this->responseHandler(false, "Error syncing payroll: " . $e->getMessage());
        }
    }

    /**
     * Get payroll date range from spreadsheet
     */
    private function getPayrollDateRange($spreadsheetId, $sheetTitle)
    {
        try {
            // Try to read date range from common cells (e.g., payout cycle dates)
            // This is a simplified version - adjust cell references based on your template
            $ranges = [
                "{$sheetTitle}!B2:B3", // Example range for dates
            ];
            
            $response = $this->sheetService->spreadsheets_values->batchGet($spreadsheetId, [
                'ranges' => $ranges
            ]);
            
            $values = $response->getValueRanges();
            
            // Default to current month if dates not found
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();
            
            // Try to parse dates from spreadsheet
            if (!empty($values) && !empty($values[0]->getValues())) {
                // Parse dates if available (implement based on your template structure)
            }
            
            return [
                'start' => $startDate,
                'end' => $endDate
            ];
        } catch (Exception $e) {
            // Default to current month on error
            return [
                'start' => Carbon::now()->startOfMonth(),
                'end' => Carbon::now()->endOfMonth()
            ];
        }
    }

    /**
     * Calculate payroll data for a user
     */
    private function calculateUserPayrollData($user, $dateRange)
    {
        $userId = $user->user_id;
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Calculate regular hours worked
        $regularHours = StafifyTimeTracking::where('user_id', $userId)
            ->whereBetween('record_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->where('status', 'completed')
            ->sum('total_hours') ?? 0;

        // Calculate overtime hours
        $overtimeHours = StafifyOvertime::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereBetween('ot_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
            ->sum('duration') ?? 0;

        // Calculate OT Rate 1, 2, 3 (assuming different multipliers)
        // You may need to adjust based on your business rules
        $otRate1Hours = $overtimeHours * 0.5; // Example: 50% of OT is Rate 1
        $otRate2Hours = $overtimeHours * 0.3; // Example: 30% of OT is Rate 2
        $otRate3Hours = $overtimeHours * 0.2; // Example: 20% of OT is Rate 3

        // Get rates from stafify_user_rates table (preferred) or fallback to user table
        $userRates = DB::table('stafify_user_rates')
            ->where('user_id', $userId)
            ->first();
        
        if ($userRates) {
            $hourlyRate = $userRates->hourly_rate ?? 0;
            $dailyRate = $userRates->daily_rate ?? 0;
            $monthlyRate = $userRates->monthly_rate ?? 0;
        } else {
            // Fallback to rates in users table if not found in stafify_user_rates
            $hourlyRate = $user->hourly_rate ?? 0;
            $dailyRate = $user->daily_rate ?? 0;
            $monthlyRate = $user->monthly_rate ?? 0;
        }

        // Calculate basic salary
        $workingDays = $startDate->diffInDays($endDate) + 1;
        $basicSalary = $monthlyRate > 0 ? $monthlyRate : ($dailyRate * ($regularHours / 8));

        // Calculate overtime pay (assuming 1.25x multiplier for Rate 1)
        $otRate1Pay = $otRate1Hours * $hourlyRate * 1.25;
        $otRate2Pay = $otRate2Hours * $hourlyRate * 1.50; // Example: 1.5x for Rate 2
        $otRate3Pay = $otRate3Hours * $hourlyRate * 2.00; // Example: 2x for Rate 3
        $totalOTPay = $otRate1Pay + $otRate2Pay + $otRate3Pay;

        // Calculate total earnings
        $totalEarnings = $basicSalary + $totalOTPay;

        // Get deminimis benefits from stafify_deminimis_benefits table
        $deminimisBenefits = DB::table('stafify_deminimis_benefits')
            ->where('user_id', $userId)
            ->first();
        
        // Use deminimis benefits if available, otherwise default to 0
        $cola = $deminimisBenefits->cola ?? 0;
        $riceSubsidy = $deminimisBenefits->rice_subsidy ?? 0;
        $mealAllowance = $deminimisBenefits->meal_allowance ?? 0;
        $uniformAllowance = $deminimisBenefits->uniform_clothing ?? 0;
        $laundryAllowance = $deminimisBenefits->laundry_allowance ?? 0;
        $medicalAllowance = $deminimisBenefits->medical_allowance ?? 0;
        $collectiveBargaining = $deminimisBenefits->collective_bargaining_agreement ?? 0;
        $totalNonTaxable13 = $deminimisBenefits->total_non_taxable_13 ?? 0;
        $serviceIncentiveLeave = $deminimisBenefits->service_incentive_leave ?? 0;
        $paidTimeOff = $deminimisBenefits->paid_time_off ?? 0;
        $otherAllowances = $deminimisBenefits->other_allowances ?? 0;
        $totalNonTaxableBenefits = $deminimisBenefits->total_non_taxable_benefits ?? 0;
        
        // Additional allowances (not in deminimis table)
        $transportationAllowance = 0;
        $housingAllowance = 0;

        // Calculate deductions (default values, adjust based on your calculation logic)
        $sssDeduction = 0;
        $phicDeduction = 0;
        $hdmfDeduction = 0;
        $withholdingTax = 0;

        // Calculate net pay
        $totalDeductions = $sssDeduction + $phicDeduction + $hdmfDeduction + $withholdingTax;
        $netPay = $totalEarnings - $totalDeductions;

        return [
            'regular_hours' => round($regularHours, 2),
            'overtime_hours' => round($overtimeHours, 2),
            'ot_rate1_hours' => round($otRate1Hours, 2),
            'ot_rate2_hours' => round($otRate2Hours, 2),
            'ot_rate3_hours' => round($otRate3Hours, 2),
            'basic_salary' => round($basicSalary, 2),
            'ot_rate1_pay' => round($otRate1Pay, 2),
            'ot_rate2_pay' => round($otRate2Pay, 2),
            'ot_rate3_pay' => round($otRate3Pay, 2),
            'total_ot_pay' => round($totalOTPay, 2),
            'total_earnings' => round($totalEarnings, 2),
            'cola' => $cola,
            'rice_subsidy' => $riceSubsidy,
            'meal_allowance' => $mealAllowance,
            'uniform_allowance' => $uniformAllowance,
            'laundry_allowance' => $laundryAllowance,
            'medical_allowance' => $medicalAllowance,
            'transportation_allowance' => $transportationAllowance,
            'housing_allowance' => $housingAllowance,
            'collective_bargaining_agreement' => $collectiveBargaining,
            'total_non_taxable_13' => $totalNonTaxable13,
            'service_incentive_leave' => $serviceIncentiveLeave,
            'paid_time_off' => $paidTimeOff,
            'other_allowances' => $otherAllowances,
            'total_non_taxable_benefits' => $totalNonTaxableBenefits,
            'sss_deduction' => $sssDeduction,
            'phic_deduction' => $phicDeduction,
            'hdmf_deduction' => $hdmfDeduction,
            'withholding_tax' => $withholdingTax,
            'total_deductions' => round($totalDeductions, 2),
            'net_pay' => round($netPay, 2),
            'working_days' => $workingDays,
        ];
    }

    /**
     * Find the starting row for data in the spreadsheet
     */
    private function findDataStartingRow($spreadsheetId, $sheetTitle)
    {
        try {
            // Read first 20 rows to find where data starts (typically after headers)
            $range = "{$sheetTitle}!A1:Z20";
            $response = $this->sheetService->spreadsheets_values->get($spreadsheetId, $range);
            $values = $response->getValues();
            
            if (empty($values)) {
                return 2; // Default to row 2 if sheet is empty
            }
            
            // Look for header row (usually row 1 or 2)
            // Find the first row that contains "Employee/Member ID No" or similar
            foreach ($values as $index => $row) {
                $rowText = implode(' ', array_map('strtolower', array_filter($row ?? [])));
                if (stripos($rowText, 'employee') !== false || stripos($rowText, 'member id') !== false) {
                    return $index + 2; // Data starts after header row
                }
            }
            
            return 2; // Default if not found
        } catch (Exception $e) {
            return 2; // Default on error
        }
    }

    /**
     * Build cell updates for a user - updates specific columns
     * Adjust column letters based on your actual spreadsheet structure
     */
    private function buildCellUpdates($user, $userData, $rowNumber, $sheetTitle)
    {
        $updates = [];
        
        // Helper function to add cell update
        $addUpdate = function($column, $value) use (&$updates, $rowNumber, $sheetTitle) {
            $updates[] = [
                'range' => "{$sheetTitle}!{$column}{$rowNumber}",
                'value' => $value
            ];
        };
        
        // Get rates from stafify_user_rates table for spreadsheet updates
        $userRates = DB::table('stafify_user_rates')
            ->where('user_id', $user->user_id)
            ->first();
        
        $hourlyRateForSheet = $userRates->hourly_rate ?? $user->hourly_rate ?? 0;
        $dailyRateForSheet = $userRates->daily_rate ?? $user->daily_rate ?? 0;
        $monthlyRateForSheet = $userRates->monthly_rate ?? $user->monthly_rate ?? 0;
        
        // Column mapping - adjust these letters based on your spreadsheet
        // A = Employee/Member ID No, B = Full Name, C = Position, etc.
        $addUpdate('A', $user->user_id); // Employee/Member ID No
        $addUpdate('B', $user->full_name ?? ($user->last_name . ', ' . $user->first_name . ' ' . ($user->middle_name ?? ''))); // ISP/EE Full Name
        $addUpdate('C', $user->user_position ?? ''); // Job/Role/Position
        $addUpdate('D', $user->user_dept ?? ''); // Department
        $addUpdate('E', $user->engagement_status ?? ($user->user_type === 'isp' ? 'ISP' : 'EE')); // Engagement Type
        $addUpdate('F', $userData['working_days']); // Working Days/Month
        $addUpdate('G', $hourlyRateForSheet ? 8 : 0); // Min. Daily Working Hours (assuming 8 hours if hourly rate exists)
        $addUpdate('H', $monthlyRateForSheet); // Monthly Rate (from stafify_user_rates)
        $addUpdate('I', $dailyRateForSheet); // Daily Rate (from stafify_user_rates)
        $addUpdate('J', $hourlyRateForSheet); // Hourly Rate (from stafify_user_rates)
        $addUpdate('M', $user->statutory_benefits ? 1 : 0); // Statutory Benefits Settings
        
        // Find column for "Total Reg. Hours Worked" - adjust column letter as needed
        // Assuming it's around column P-Q based on typical payroll structure
        // You'll need to adjust these column letters to match your spreadsheet
        $addUpdate('P', $userData['regular_hours']); // Total Reg. Hours Worked (adjust column)
        
        // Overtime columns - adjust column letters as needed
        $addUpdate('Q', $userData['ot_rate1_hours']); // OT Rate 1 Hours (adjust column)
        $addUpdate('R', 1.25); // OT Rate 1 Multiplier
        $addUpdate('S', $hourlyRateForSheet); // OT Rate 1 Base Rate (from stafify_user_rates)
        $addUpdate('T', $userData['ot_rate1_pay']); // Total OT Rate 1 Pay
        
        $addUpdate('U', $userData['ot_rate2_hours']); // OT Rate 2 Hours
        $addUpdate('V', 1.50); // OT Rate 2 Multiplier
        $addUpdate('W', $hourlyRateForSheet); // OT Rate 2 Base Rate (from stafify_user_rates)
        $addUpdate('X', $userData['ot_rate2_pay']); // Total OT Rate 2 Pay
        
        $addUpdate('Y', $userData['ot_rate3_hours']); // OT Rate 3 Hours
        $addUpdate('Z', 2.00); // OT Rate 3 Multiplier
        $addUpdate('AA', $hourlyRateForSheet); // OT Rate 3 Base Rate (from stafify_user_rates)
        $addUpdate('AB', $userData['ot_rate3_pay']); // Total OT Rate 3 Pay
        
        // Basic salary and earnings
        $addUpdate('AC', $userData['basic_salary']); // Total Basic Salary (adjust column)
        $addUpdate('AD', $userData['total_earnings']); // Total Earnings (adjust column)
        
        // Allowances (non-taxable) - from stafify_deminimis_benefits
        $addUpdate('AE', $userData['cola']); // Cola (adjust column)
        $addUpdate('AF', $userData['rice_subsidy']); // Rice Subsidy (from stafify_deminimis_benefits)
        $addUpdate('AG', $userData['meal_allowance']); // Meal Allowance (from stafify_deminimis_benefits)
        $addUpdate('AH', $userData['uniform_allowance']); // Uniform Clothing Allowance (from stafify_deminimis_benefits)
        $addUpdate('AI', $userData['laundry_allowance']); // Laundry Allowance (from stafify_deminimis_benefits)
        $addUpdate('AJ', $userData['medical_allowance']); // Medical Allowance (from stafify_deminimis_benefits)
        $addUpdate('AK', $userData['transportation_allowance']); // Transportation Allowance
        $addUpdate('AL', $userData['housing_allowance']); // Housing Allowance
        $addUpdate('AM', $userData['collective_bargaining_agreement'] ?? 0); // Collective Bargaining Agreement (from stafify_deminimis_benefits)
        $addUpdate('AN', $userData['total_non_taxable_13'] ?? 0); // Total Non-Taxable 13th Mo. Pay (from stafify_deminimis_benefits)
        $addUpdate('AO', $userData['service_incentive_leave'] ?? 0); // Service Incentive Leaves (from stafify_deminimis_benefits)
        $addUpdate('AP', $userData['paid_time_off'] ?? 0); // Paid Time-Off (from stafify_deminimis_benefits)
        $addUpdate('AQ', $userData['other_allowances'] ?? 0); // Other Allowances/Bonuses (from stafify_deminimis_benefits)
        $addUpdate('AR', $userData['total_non_taxable_benefits'] ?? 0); // Total Non Taxable Benefits (from stafify_deminimis_benefits)
        
        // Deductions (note: column letters shifted due to added deminimis columns)
        $addUpdate('AS', $userData['sss_deduction']); // SSS Deduction (adjust column - note: shifted from AM to AS)
        $addUpdate('AT', $userData['phic_deduction']); // PHIC Deduction (adjust column)
        $addUpdate('AU', $userData['hdmf_deduction']); // HDMF Deduction (adjust column)
        $addUpdate('AV', $userData['withholding_tax']); // BIR Withholding Tax (adjust column)
        
        // Net Pay
        $addUpdate('AW', $userData['net_pay']); // Net Pay (adjust column - note: shifted from AQ to AW)
        
        // NOTE: The column letters above are examples. You MUST adjust them to match your actual spreadsheet columns.
        // Look at your spreadsheet and map each field to the correct column letter.
        
        return $updates;
    }

    private function getPayrollSettings()
    {
        $settings = PayrollSettings::first();
        return $settings ?: (object) [
            'id' => 0,
            'frequency' => 0,
            'disbursement' => 0,
            'deduction_schedule' => '',
            'benefits_url' => ''
        ];
    }

    private function updatePayrollSettings($id, $data)
    {
        $settings = PayrollSettings::find($id);
        
        if (!$settings) {
            return $this->responseHandler(false, "Settings not found");
        }

        $settings->update([
            'frequency' => $data['frequency'],
            'disbursement' => $data['disbursement'],
            'deduction_schedule' => $data['deduction_schedule'],
        ]);

        return $this->responseHandler(true, "Settings updated successfully");
    }
}
