<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DgClass;
use App\Models\DgSegregationRule;

class DgSeeder extends Seeder
{
    public function run(): void
    {
        // 1. IMDG Classes
        $data = [
            ['1.1', 'Explosives: Mass Explosion Hazard'],
            ['1.2', 'Explosives: Projection Hazard'],
            ['1.3', 'Explosives: Fire Hazard'],
            ['1.4', 'Explosives: Minor Hazard'],
            ['2.1', 'Flammable Gases'],
            ['2.2', 'Non-Flammable, Non-Toxic Gases'],
            ['2.3', 'Toxic Gases'],
            ['3',   'Flammable Liquids'],
            ['4.1', 'Flammable Solids'],
            ['5.1', 'Oxidizing Substances'],
            ['6.1', 'Toxic Substances'],
            ['8',   'Corrosives'],
            ['9',   'Miscellaneous Dangerous Goods'],
        ];

        $classes = [];
        foreach ($data as $d) {
            $classes[$d[0]] = DgClass::create(['class_code' => $d[0], 'name' => $d[1]]);
        }

        // 2. Segregation Rules (Simplified)
        // Correct IMDG segregation is complex; implementing key rules for demo.
        
        // 1.1 vs 3 (Explosives vs Flammable Liquids) = Prohibited/Separated Longitudinally
        DgSegregationRule::create([
            'class_a_id' => $classes['1.1']->id,
            'class_b_id' => $classes['3']->id,
            'rule' => 'prohibited'
        ]);

        // 1.1 vs 2.1 (Explosives vs Flammable Gases) = Prohibited
        DgSegregationRule::create([
            'class_a_id' => $classes['1.1']->id,
            'class_b_id' => $classes['2.1']->id,
            'rule' => 'prohibited'
        ]);
        
        // 8 vs 3 (Corrosives vs Flammable Liquids) = Allowed
        DgSegregationRule::create([
            'class_a_id' => $classes['8']->id,
            'class_b_id' => $classes['3']->id,
            'rule' => 'allowed'
        ]);
    }
}
