<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payroll\App\Http\Requests\GeneratePayrollRequest;
use Modules\Payroll\App\Http\Requests\UpdatePayrollRequest;
use Modules\Payroll\App\Models\Payroll;
use Modules\Payroll\App\Services\PayrollService;

class PayrollController extends Controller
{
    protected PayrollService $service;

    public function __construct(PayrollService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $month = $request->query('month');

        $payrolls = Payroll::with('employee')
            ->when($month, fn ($query) => $query->where('month', $month))
            ->latest('month')
            ->paginate(20)
            ->withQueryString();

        return view('payroll::payrolls.index', compact('payrolls', 'month'));
    }

    public function create()
    {
        return view('payroll::payrolls.create');
    }

    public function store(GeneratePayrollRequest $request)
    {
        $payrolls = $this->service->generatePayroll($request->input('month'));

        return redirect()
            ->route('payroll.index')
            ->with('success', "Payroll generated for {$request->input('month')} ({$payrolls->count()} records).");
    }

    public function show(Payroll $payroll)
    {
        $payroll->load('employee');

        return view('payroll::payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll)
    {
        $payroll->load('employee');

        return view('payroll::payrolls.edit', compact('payroll'));
    }

    public function update(UpdatePayrollRequest $request, Payroll $payroll)
    {
        $this->service->updatePayroll($payroll, $request->validated());

        return redirect()
            ->route('payroll.edit', $payroll)
            ->with('success', 'Payroll updated successfully.');
    }

    public function destroy(Payroll $payroll)
    {
        $payroll->delete();

        return redirect()
            ->route('payroll.index')
            ->with('success', 'Payroll record deleted successfully.');
    }
}
