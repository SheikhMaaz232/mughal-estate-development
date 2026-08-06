<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Payroll\App\Models\Employee;

class ContractController extends Controller
{
    public function view(Employee $employee)
    {
        $employee->load(['department', 'designation', 'shift']);

        return view('contracts.employee-contract', [
            'employee' => $employee,
            'generatedAt' => now()->translatedFormat('d F Y'),
        ]);
    }


}
