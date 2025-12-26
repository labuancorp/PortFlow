<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WorkPermit;
use App\Models\User;
use Illuminate\Support\Str;

class HSESeeder extends Seeder
{
    public function run(): void
    {
        $approver = User::where('role', 'admin')->first() ?? User::first();

        // 1. Approved Hot Work Permit (Active)
        WorkPermit::create([
            'control_no' => 'PTW-2025-001',
            'type' => 'hot_work',
            'location' => 'Wharf 3 - Crane Rail Repair',
            'applicant_name' => 'TechnoWeld Services',
            'approved_by' => $approver->id,
            'valid_from' => now()->subHours(2),
            'valid_to' => now()->addHours(4), // Active for 4 more hours
            'status' => 'active',
            'description' => 'Welding repair on crane tracks. Fire watch in place.',
        ]);

        // 2. Requested Confined Space
        WorkPermit::create([
            'control_no' => 'PTW-2025-002',
            'type' => 'confined_space',
            'location' => 'Underground Utility Tunnel A',
            'applicant_name' => 'City Maintenance Crew',
            'valid_from' => now()->addDays(1)->hour(9),
            'valid_to' => now()->addDays(1)->hour(17),
            'status' => 'requested',
            'description' => 'Cable inspection in tunnel.',
        ]);

        // 3. Rejected Working at Height
        WorkPermit::create([
            'control_no' => 'PTW-2025-003',
            'type' => 'working_at_height',
            'location' => 'Warehouse Roof',
            'applicant_name' => 'FastFix Roofing',
            'approved_by' => $approver->id,
            'valid_from' => now()->subDays(1),
            'valid_to' => now()->subDays(1)->addHours(8),
            'status' => 'rejected',
            'description' => 'Routine roof maintenance during rain.',
        ]);

        // 4. Closed Electrical Permit
        WorkPermit::create([
            'control_no' => 'PTW-2025-004',
            'type' => 'electrical',
            'location' => 'Substation B',
            'applicant_name' => 'PowerGrid Engineers',
            'approved_by' => $approver->id,
            'valid_from' => now()->subDays(2)->hour(8),
            'valid_to' => now()->subDays(2)->hour(16),
            'status' => 'closed',
            'description' => 'Switchgear replacement.',
        ]);
        
        // 5. High Risk Clash Test (For testing clash detection)
        // This one is requested for the SAME TIME as permit #1 but different location, 
        // to show a valid second permit.
        WorkPermit::create([
            'control_no' => 'PTW-2025-005',
            'type' => 'cold_work',
            'location' => 'Office Building Renovation',
            'applicant_name' => 'Interior Designs',
            'valid_from' => now()->subHours(1),
            'valid_to' => now()->addHours(2),
            'status' => 'requested',
            'description' => 'Painting office walls.',
        ]);
    }
}
