<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Modules\Payroll\App\Http\Requests\StoreEmployeeRequest;
use Modules\Payroll\App\Http\Requests\UpdateEmployeeRequest;
use Modules\Payroll\App\Models\Allowance;
use Modules\Payroll\App\Models\Deduction;
use Modules\Payroll\App\Models\Designation;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Models\LeaveType;
use Modules\Payroll\App\Services\EmployeeService;

class EmployeeController extends Controller
{
    protected $employeeService;

    public function __construct(EmployeeService $employeeService)
    {
        $this->employeeService = $employeeService;
    }

     private function getMasterData()
    {
        return [
            'designations' => Cache::remember('designations_data', 3600, fn() =>
            Designation::select('id', 'title_en', 'title_ur')->get()),

            'banks' => Cache::remember('banks_data', 3600, fn() =>
            Bank::select('id', 'name_en', 'name_ur')->get()),

            'allowances' => Cache::remember('allowances_data', 3600, fn() =>
            Allowance::select('id', 'title_en', 'title_ur')->get()),

            'deductions' => Cache::remember('deductions_data', 3600, fn() =>
            Deduction::select('id', 'title_en', 'title_ur')->get())

        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employeesData = Employee::latest()->paginate(10);
        return view('payroll::employees.index', compact('employeesData') , $this->getMasterData());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaveTypes = LeaveType::orderBy('title_en')->get();

        return view('payroll::employees.create', compact('leaveTypes'), $this->getMasterData());

    }

    /**
     * Store a newly created resource in storage.
     */
       public function store(Request $request)
        {
            try {
                $employee = $this->employeeService->createEmployee($request->all());

                return redirect()->route('payroll.employees.index')
                    ->with('success', __('messages.record-saved'));
            } catch (\Exception $e) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', __('Error creating employee: ') . $e->getMessage());
            }
        }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        $employee->load(['contacts', 'banks', 'allowances', 'deductions', 'leaveBalances.leaveType']);
        $leaveTypes = LeaveType::orderBy('title_en')->get();

        return view('payroll::employees.edit', compact('employee', 'leaveTypes'), $this->getMasterData());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
         try {
                $this->employeeService->updateEmployee($employee, $request->all());

                return redirect()->route('payroll.employees.index')
                    ->with('success', __('messages.record-updated'));
             } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error updating employee: ') . $e->getMessage());
             }
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        try {
            // Load employee with all relationships
            $employee->load([
                'contacts',
                'banks',
                'allowances.allowance',
                'deductions.deduction',
                'leaveBalances.leaveType',
                'department',
                'designation'
            ]);

            return view('payroll::employees.show', compact('employee'));
        } catch (\Exception $e) {
            return redirect()->route('payroll.employees.index')
                ->with('error', __('Error viewing employee: ') . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        try{
            $employee->delete();

            return redirect()->route('payroll.employees.index')
                    ->with('success', __('messages.record-updated'));
             } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', __('Error updating employee: ') . $e->getMessage());
            }
    }
}
