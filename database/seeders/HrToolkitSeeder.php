<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HrToolkit;
use App\Models\User;

class HrToolkitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin user or create a default user for HR toolkit entries
        $adminUser = User::first();
        
        if (!$adminUser) {
            // If no users exist, we'll use user_id = 1 as a fallback
            $adminUser = (object) ['user_id' => 1];
        }

        $hrToolkitData = [
            [
                'user_id' => $adminUser->user_id,
                'sales_title' => 'E-Settlement Account Enrollment',
                'form_url' => null,
                'response_url' => null,
                'icon' => 'Settlement.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
            [
                'user_id' => $adminUser->user_id,
                'sales_title' => 'E-DISC Personality Test',
                'form_url' => null,
                'response_url' => null,
                'icon' => 'Personality.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
            [
                'user_id' => $adminUser->user_id,
                'sales_title' => 'E-Acknowledgement of Company Policy',
                'form_url' => null,
                'response_url' => null,
                'icon' => 'Policy.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
            [
                'user_id' => $adminUser->user_id,
                'sales_title' => 'E-ID Enrollment',
                'form_url' => null,
                'response_url' => null,
                'icon' => 'Register.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
            [
                'user_id' => $adminUser->user_id,
                'sales_title' => 'E-Incident Report',
                'form_url' => null,
                'response_url' => null,
                'icon' => 'Write.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
            [
                'user_id' => $adminUser->user_id,
                'sales_title' => 'E-NTE Request',
                'form_url' => null,
                'response_url' => null,
                'icon' => 'Request.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
            [
                'user_id' => $adminUser->user_id,
                'sales_title' => 'E-NTE Submission',
                'form_url' => null,
                'response_url' => null,
                'icon' => 'Write.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
            [
                'user_id' => $adminUser->user_id,
                'sales_title' => 'E-Admin Hearing',
                'form_url' => null,
                'response_url' => null,
                'icon' => 'consultation.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
            [
                'user_id' => $adminUser->user_id,
                'sales_title' => 'E-Notice of Decision',
                'form_url' => null,
                'response_url' => null,
                'icon' => 'Decision.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
            [
                'user_id' => $adminUser->user_id,
                'sales_title' => 'E-Certification',
                'form_url' => null,
                'response_url' => null,
                'icon' => 'Star.gif',
                'type' => 'Form',
                'is_approved' => true,
            ],
        ];

        foreach ($hrToolkitData as $data) {
            HrToolkit::updateOrCreate(
                [
                    'sales_title' => $data['sales_title'],
                ],
                $data
            );
        }
    }
}
