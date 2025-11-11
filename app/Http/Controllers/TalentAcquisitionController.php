<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TalentAcquisitionService;
use App\Models\TalentAcquisitionToolkit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class TalentAcquisitionController extends Controller
{
    protected $talentAcqService;

    public function __construct(TalentAcquisitionService $talentAcqService)
    {
        $this->talentAcqService = $talentAcqService;
    }

    public function index()
    {
        $user = Auth::user();
        $toolkits = TalentAcquisitionToolkit::accessible($user->user_id)
            ->orderBy('created_at', 'asc')
            ->get();

        $iconsPath = public_path('HRIS/hr-toolkit/assets/crm_hr/');
        $icons = [];
        if (is_dir($iconsPath)) {
            $files = scandir($iconsPath);
            $icons = array_filter($files, fn($file) => !in_array($file, ['.', '..']) && pathinfo($file, PATHINFO_EXTENSION) === 'gif');
        }

        return view('talent-acquisition', compact('toolkits', 'icons'));
    }

    public function getStaffData(Request $request)
    {
        try {
            $toolkitId = $request->input('toolkit_id');
            $sheetName = $request->input('sheet');

            $staffData = [];
            $availableSheets = [];
            $currentSheet = $sheetName;

            if ($toolkitId) {
                $toolkit = TalentAcquisitionToolkit::find($toolkitId);
                
                // Ensure we have a toolkit and it has a response URL (Google Sheet)
                if ($toolkit && $toolkit->response_url) {
                     $spreadsheetId = $this->talentAcqService->getSpreadsheetIdFromUrl($toolkit->response_url);
                     if ($spreadsheetId) {
                         // 1. Fetch all available sheets for the dropdown
                         $availableSheets = $this->talentAcqService->getSheetNames($spreadsheetId);
                         
                         // 2. Determine which sheet to load (requested one, or default to first visible)
                         if (empty($currentSheet) && !empty($availableSheets)) {
                             $currentSheet = $availableSheets[0];
                         }

                         // 3. Fetch data for the selected sheet
                         if ($currentSheet) {
                             $staffData = $this->talentAcqService->getDynamicData($spreadsheetId, $currentSheet);
                         }
                     }
                }
            }

            $staffCollection = new Collection($staffData);
            
            // Prepare filter options based on the FULL dataset of the CURRENT sheet
            $applyingAsOptions = $staffCollection->pluck('applyingAs')->filter()->unique()->values()->all();
            $statusOptions = $staffCollection->pluck('status')->filter()->unique()->values()->all();

            // Apply filters
            $filterRole = $request->input('role');
            $filterStatus = $request->input('status');
            $searchQuery = $request->input('search');

            $filteredData = $staffCollection->filter(function ($row) use ($filterRole, $filterStatus, $searchQuery) {
                 $matchesRole = empty($filterRole) || ($row['applyingAs'] ?? '') === $filterRole;
                 $matchesStatus = empty($filterStatus) || ($row['status'] ?? '') === $filterStatus;
                 
                 $fullName = ($row['firstName'] ?? '') . ' ' . ($row['lastName'] ?? '');
                 $matchesSearch = empty($searchQuery) || stripos($fullName, $searchQuery) !== false;
                 
                 return $matchesRole && $matchesStatus && $matchesSearch;
            });

            // Pagination
            $itemsPerPage = (int)$request->input('items', 20);
            $currentPage = (int)$request->input('page', 1);
            $totalItems = $filteredData->count();
            $totalPages = ceil($totalItems / $itemsPerPage) ?: 1;
            
            if ($currentPage > $totalPages) $currentPage = $totalPages;
            if ($currentPage < 1) $currentPage = 1;

            $paginatedData = $filteredData->slice(($currentPage - 1) * $itemsPerPage, $itemsPerPage)->values();

            return response()->json([
                'success' => true,
                'data' => $paginatedData,
                'pagination' => [
                    'totalItems' => $totalItems,
                    'totalPages' => $totalPages,
                    'currentPage' => $currentPage,
                    'itemsPerPage' => $itemsPerPage
                ],
                'filters' => [
                    'applyingAsOptions' => $applyingAsOptions, 
                    'statusOptions' => $statusOptions
                ],
                'sheets' => $availableSheets,
                'currentSheet' => $currentSheet
            ]);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
         $validator = Validator::make($request->all(), [
            'toolkit_id' => 'required|integer',
            'sheetName' => 'required|string',
            'candidateEmail' => 'required|email',
            'newStatus' => 'required|string',
        ]);

        if ($validator->fails()) return response()->json(['success' => false, 'message' => $validator->errors()->first()], 400);

        try {
            $toolkit = TalentAcquisitionToolkit::find($request->input('toolkit_id'));
            if (!$toolkit || !$toolkit->response_url) {
                throw new Exception("Invalid Toolkit or missing Sheet URL.");
            }

            $spreadsheetId = $this->talentAcqService->getSpreadsheetIdFromUrl($toolkit->response_url);
            if (!$spreadsheetId) {
                throw new Exception("Invalid Spreadsheet ID.");
            }

            $res = $this->talentAcqService->updateCandidateStatus(
                $spreadsheetId,
                $request->input('sheetName'),
                $request->input('candidateEmail'),
                $request->input('newStatus')
            );

            return response()->json(['success' => true, 'message' => 'Status updated', 'result' => $res]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        return $this->saveToolkit($request);
    }

    public function update(Request $request, $id)
    {
        return $this->saveToolkit($request, $id);
    }

    private function saveToolkit(Request $request, $id = null)
    {
        $validator = Validator::make($request->all(), [
            'sales_title' => 'required|string|max:50',
            'icon' => 'required|string',
            'type' => 'required|in:Form,Sheet,Video,Slides,Folder,Form+Sheet,Talent Pipeline (Sheet)',
        ]);

        if ($validator->fails()) return redirect()->back()->withErrors($validator)->withInput();

        $type = $request->input('type');
        $formUrl = $request->input('form_url');
        $responseUrl = $request->input('response_url');

        // Handle Merged Quick Links for Talent Pipeline
        if ($type === 'Talent Pipeline (Sheet)') {
            if (!$request->filled('response_url')) {
                 return redirect()->back()->withErrors(['response_url' => 'Main Sheet URL is required for cards.'])->withInput();
            }

            $quickLinks = [];
            $linkTitles = $request->input('ql_title', []);
            $linkUrls = $request->input('ql_url', []);
            $linkTypes = $request->input('ql_type', []);

            foreach ($linkTitles as $index => $title) {
                if (!empty($title) && !empty($linkUrls[$index])) {
                    $quickLinks[] = [
                        'title' => $title,
                        'url' => $linkUrls[$index],
                        'type' => $linkTypes[$index] ?? 'link'
                    ];
                }
            }
            $formUrl = !empty($quickLinks) ? json_encode($quickLinks) : null;
        } 
        else {
             $rules = match($type) {
                'Video', 'Slides', 'Folder', 'Form' => ['form_url' => 'required'],
                'Sheet' => ['response_url' => 'required'],
                'Form+Sheet' => ['form_url' => 'required', 'response_url' => 'required'],
                default => []
            };
            foreach ($rules as $field => $rule) {
                if (!$request->filled($field)) return redirect()->back()->withErrors([$field => 'Required for this type.'])->withInput();
            }
        }

        try {
            $data = [
                'user_id' => Auth::id(),
                'sales_title' => $request->input('sales_title'),
                'form_url' => $formUrl,
                'response_url' => $responseUrl,
                'icon' => $request->input('icon'),
                'type' => $type,
            ];

            // Only reset approval if it's a new entry or explicitly requested. 
            // You might want to keep it approved if just editing titles/links.
            if (!$id) {
                 $data['is_approved'] = false;
            }

            if ($id) {
                $toolkit = TalentAcquisitionToolkit::findOrFail($id);
                if ($toolkit->user_id !== Auth::id()) throw new Exception("Unauthorized");
                $toolkit->update($data);
                $msg = 'Toolkit updated successfully.';
            } else {
                TalentAcquisitionToolkit::create($data);
                $msg = 'Toolkit created successfully.';
            }

            return redirect()->route('talent-acquisition.index')->with('success', $msg);
        } catch (Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}