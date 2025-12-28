<?php

namespace App\Livewire\Dg;

use Livewire\Component;
use App\Services\DgComplianceService;
use App\Models\DgDeclaration;
use App\Models\DgClass;
use Livewire\Attributes\Layout;

class ComplianceDashboard extends Component
{
    // Compatibility Checker Tool
    public $checkClassA;
    public $checkClassB;
    public $compatibilityResult = null;

    #[Layout('components.layouts.app')]
    public function render(DgComplianceService $service)
    {
        $bunkerStatus = $service->getBunkerStatus();
        $declarations = DgDeclaration::with('dgClass')->latest()->get();
        $classes = DgClass::orderBy('class_code')->get();

        return view('livewire.dg.compliance-dashboard', [
            'bunkerStatus' => $bunkerStatus,
            'declarations' => $declarations,
            'classes' => $classes
        ]);
    }

    public function checkSegregation(DgComplianceService $service)
    {
        if ($this->checkClassA && $this->checkClassB) {
            $this->compatibilityResult = $service->checkSegregation($this->checkClassA, $this->checkClassB);
        }
    }
}
