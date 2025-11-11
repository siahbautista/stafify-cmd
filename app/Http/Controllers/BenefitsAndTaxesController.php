<?php

namespace App\Http\Controllers;

use App\Models\SalesToolkit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BenefitsAndTaxesController extends Controller
{
    /**
     * Display the Benefits & Taxes page.
     */
    public function index()
    {
        $user = Auth::user();
        $toolkits = SalesToolkit::accessible($user->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get available icons from the benefits-and-taxes assets folder
        $iconsPath = public_path('HRIS' . DIRECTORY_SEPARATOR . 'benefits-and-taxes' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'crm_sales' . DIRECTORY_SEPARATOR);
        $icons = [];
        
        if (is_dir($iconsPath)) {
            $files = scandir($iconsPath);
            $icons = array_filter($files, function($file) {
                return pathinfo($file, PATHINFO_EXTENSION) === 'gif';
            });
        }

        return view('benefits-and-taxes', compact('toolkits', 'icons', 'iconsPath'));
    }

    /**
     * Store a newly created toolkit.
     */
    public function store(Request $request)
    {
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

        $user = Auth::user();
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
            SalesToolkit::create([
                'user_id' => $user->user_id,
                'sales_title' => $request->input('sales_title'),
                'form_url' => $request->input('form_url'),
                'response_url' => $request->input('response_url'),
                'icon' => $request->input('icon'),
                'type' => $type,
                'is_approved' => false,
            ]);

            return redirect()->route('benefits-and-taxes')
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
        $toolkit = SalesToolkit::findOrFail($id);
        $user = Auth::user();

        // Check if user owns this toolkit
        if ($toolkit->user_id !== $user->user_id) {
            return redirect()->back()
                ->withErrors(['error' => 'You can only edit your own toolkits.']);
        }

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
            $toolkit->update([
                'sales_title' => $request->input('sales_title'),
                'form_url' => $request->input('form_url'),
                'response_url' => $request->input('response_url'),
                'icon' => $request->input('icon'),
                'type' => $type,
                'is_approved' => false, // Reset approval status on update
            ]);

            return redirect()->route('benefits-and-taxes')
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
            case 'Website':
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
