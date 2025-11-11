<?php

namespace Database\Seeders;

use App\Models\LegalToolkit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LegalToolkitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $legalDocuments = [
            [
                'user_id' => 1, // Assuming admin user with ID 1
                'sales_title' => 'E-Employment Contract',
                'form_url' => '#', // Placeholder - to be updated with actual form URL
                'response_url' => '#', // Placeholder - to be updated with actual response URL
                'icon' => 'Write.gif',
                'type' => 'Form+Sheet',
                'is_approved' => true,
            ],
            [
                'user_id' => 1, // Assuming admin user with ID 1
                'sales_title' => 'E-Service Agreement',
                'form_url' => '#', // Placeholder - to be updated with actual form URL
                'response_url' => '#', // Placeholder - to be updated with actual response URL
                'icon' => 'Settlement.gif',
                'type' => 'Form+Sheet',
                'is_approved' => true,
            ],
            [
                'user_id' => 1, // Assuming admin user with ID 1
                'sales_title' => 'E-NDA',
                'form_url' => '#', // Placeholder - to be updated with actual form URL
                'response_url' => '#', // Placeholder - to be updated with actual response URL
                'icon' => 'Policy.gif',
                'type' => 'Form+Sheet',
                'is_approved' => true,
            ],
        ];

        foreach ($legalDocuments as $document) {
            LegalToolkit::create($document);
        }
    }
}
