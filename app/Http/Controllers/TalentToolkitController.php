<?php

namespace App\Http\Controllers;

use App\Models\TalentToolkit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class TalentToolkitController extends Controller
{
    /**
     * Display the Talent Toolkit page.
     */
    public function index()
    {
        $user = Auth::user();
        $toolkits = TalentToolkit::accessible($user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get available icons from the assets folder
        $iconsPath = public_path('HRIS/hr-toolkit/assets/crm_hr/');
        $icons = [];
        
        if (is_dir($iconsPath)) {
            $files = scandir($iconsPath);
            $icons = array_filter($files, function($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'gif';
            });
        }

        return view('talent-management', compact('toolkits', 'icons', 'iconsPath'));
    }

    /**
     * Store a newly created toolkit.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'sales_title' => 'required|string|max:50',
            'form_url' => 'nullable',
            'response_url' => 'nullable',
            'icon' => 'required|string',
            'type' => 'required|in:Form,Sheet,Video,Slides,Folder,Form+Sheet,Website,Multiple Forms + Sheets',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $type = $request->input('type');

        // Validate required fields based on type
        $validationRules = $this->getValidationRulesForType($type);
        foreach ($validationRules as $field => $rule) {
            if ($rule === 'required' && empty($request->input($field))) {
                return redirect()->back()
                    ->withErrors([$field => 'This field is required for the selected type.'])
                    ->withInput();
            }
        }

        // Handle Multiple Forms + Sheets type - store as JSON
        $formUrl = $request->input('form_url');
        $responseUrl = $request->input('response_url');
        
        if ($type === 'Multiple Forms + Sheets') {
            // Collect all form URLs
            $formUrls = [];
            for ($i = 1; $i <= 6; $i++) {
                $formUrlInput = $request->input("form_url_{$i}");
                if (!empty($formUrlInput)) {
                    $formUrls[] = $formUrlInput;
                }
            }
            
            // Collect all sheet URLs
            $sheetUrls = [];
            for ($i = 1; $i <= 6; $i++) {
                $sheetUrlInput = $request->input("sheet_url_{$i}");
                if (!empty($sheetUrlInput)) {
                    $sheetUrls[] = $sheetUrlInput;
                }
            }
            
            // Validate that at least one form and one sheet URL is provided
            if (empty($formUrls) || empty($sheetUrls)) {
                return redirect()->back()
                    ->withErrors(['error' => 'Please provide at least one Form URL and one Sheet URL for Multiple Forms + Sheets type.'])
                    ->withInput();
            }
            
            $formUrl = json_encode($formUrls);
            $responseUrl = json_encode($sheetUrls);
        }

        try {
            $toolkit = TalentToolkit::create([
                'user_id' => $user->user_id,
                'sales_title' => $request->input('sales_title'),
                'form_url' => $formUrl,
                'response_url' => $responseUrl,
                'icon' => $request->input('icon'),
                'type' => $type,
                'is_approved' => false,
            ]);

            return redirect()->route('talent-management')
                ->with('success', 'Toolkit created successfully and is pending approval.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create toolkit. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Update the specified toolkit.
     */
    public function update(Request $request, $id)
    {
        $toolkit = TalentToolkit::findOrFail($id);
        $user = Auth::user();

        // Check if user owns this toolkit
        if ($toolkit->user_id !== $user->user_id) {
            return redirect()->back()
                ->withErrors(['error' => 'You can only edit your own toolkits.']);
        }

        $validator = Validator::make($request->all(), [
            'sales_title' => 'required|string|max:50',
            'form_url' => 'nullable',
            'response_url' => 'nullable',
            'icon' => 'required|string',
            'type' => 'required|in:Form,Sheet,Video,Slides,Folder,Form+Sheet,Website,Multiple Forms + Sheets',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $type = $request->input('type');

        // Validate required fields based on type
        $validationRules = $this->getValidationRulesForType($type);
        foreach ($validationRules as $field => $rule) {
            if ($rule === 'required' && empty($request->input($field))) {
                return redirect()->back()
                    ->withErrors([$field => 'This field is required for the selected type.'])
                    ->withInput();
            }
        }

        // Handle Multiple Forms + Sheets type - store as JSON
        $formUrl = $request->input('form_url');
        $responseUrl = $request->input('response_url');
        
        if ($type === 'Multiple Forms + Sheets') {
            // Collect all form URLs
            $formUrls = [];
            for ($i = 1; $i <= 6; $i++) {
                $formUrlInput = $request->input("form_url_{$i}");
                if (!empty($formUrlInput)) {
                    $formUrls[] = $formUrlInput;
                }
            }
            
            // Collect all sheet URLs
            $sheetUrls = [];
            for ($i = 1; $i <= 6; $i++) {
                $sheetUrlInput = $request->input("sheet_url_{$i}");
                if (!empty($sheetUrlInput)) {
                    $sheetUrls[] = $sheetUrlInput;
                }
            }
            
            // Validate that at least one form and one sheet URL is provided
            if (empty($formUrls) || empty($sheetUrls)) {
                return redirect()->back()
                    ->withErrors(['error' => 'Please provide at least one Form URL and one Sheet URL for Multiple Forms + Sheets type.'])
                    ->withInput();
            }
            
            $formUrl = json_encode($formUrls);
            $responseUrl = json_encode($sheetUrls);
        }

        try {
            $toolkit->update([
                'sales_title' => $request->input('sales_title'),
                'form_url' => $formUrl,
                'response_url' => $responseUrl,
                'icon' => $request->input('icon'),
                'type' => $type,
                'is_approved' => false, // Reset approval status on update
            ]);

            return redirect()->route('talent-management')
                ->with('success', 'Toolkit updated successfully and is pending approval.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to update toolkit. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Get validation rules based on toolkit type.
     */
    private function getValidationRulesForType($type)
    {
        switch ($type) {
            case 'Video':
            case 'Slides':
            case 'Folder':
                return ['form_url' => 'required'];
            case 'Sheet':
                return ['response_url' => 'required'];
            case 'Form':
                return ['form_url' => 'required'];
            case 'Form+Sheet':
                return [
                    'form_url' => 'required',
                    'response_url' => 'required'
                ];
            case 'Multiple Forms + Sheets':
                // Validate that at least one form and one sheet URL is provided
                return [
                    'form_url_1' => 'nullable|url',
                    'form_url_2' => 'nullable|url',
                    'form_url_3' => 'nullable|url',
                    'form_url_4' => 'nullable|url',
                    'form_url_5' => 'nullable|url',
                    'form_url_6' => 'nullable|url',
                    'sheet_url_1' => 'nullable|url',
                    'sheet_url_2' => 'nullable|url',
                    'sheet_url_3' => 'nullable|url',
                    'sheet_url_4' => 'nullable|url',
                    'sheet_url_5' => 'nullable|url',
                    'sheet_url_6' => 'nullable|url',
                ];
            default:
                return [];
        }
    }

    /**
     * Get icon name without extension.
     */
    public function getIconName($filename)
    {
        return ucfirst(pathinfo($filename, PATHINFO_FILENAME));
    }
}
