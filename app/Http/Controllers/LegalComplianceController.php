<?php

namespace App\Http\Controllers;

use App\Models\LegalToolkit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class LegalComplianceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $legal_toolkits = LegalToolkit::accessible($user->user_id)
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

        return view('legal-and-compliance', compact('legal_toolkits', 'icons', 'iconsPath'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        $validator = Validator::make($request->all(), [
            'sales_title' => 'required|string|max:50',
            'form_url' => 'nullable|url',
            'response_url' => 'nullable|url',
            'icon' => 'required|string',
            'type' => 'required|in:Form,Sheet,Video,Slides,Folder,Form+Sheet,Website',
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

        try {
            $toolkit = LegalToolkit::create([
                'user_id' => $user->user_id,
                'sales_title' => $request->input('sales_title'),
                'form_url' => $request->input('form_url'),
                'response_url' => $request->input('response_url'),
                'icon' => $request->input('icon'),
                'type' => $type,
                'is_approved' => false,
            ]);

            return redirect()->route('legal-and-compliance')
                ->with('success', 'Legal document created successfully and is pending approval.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Failed to create legal document. Please try again.'])
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
