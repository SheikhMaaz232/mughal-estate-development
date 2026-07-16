<?php

namespace Modules\Payroll\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Payroll\App\Models\Attendance;
use Modules\Payroll\App\Models\Employee;
use Modules\Payroll\App\Services\AttendanceService;

class AttendanceController extends Controller
{
    protected $service;

    public function __construct(AttendanceService $service)
    {
        $this->service = $service;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attendances = Attendance::with('employee')->latest()->paginate(20);
        return view('payroll::attendance.index', compact('attendances'));
    }

    public function process(Request $request)
    {
        $this->service->processDate($request->date);
        return back()->with('success', __('payroll::messages.attendance-processed-for-date', ['date' => $request->date]));
    }

    public function manual()
    {
        $employees = Employee::all();
        return view('payroll::attendance.manual', compact('employees'));
    }

    public function storeManual(Request $request)
    {
        $this->service->markManual($request->all());
        return back()->with('success', __('payroll::messages.manual-attendance-saved'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payroll::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('payroll::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('payroll::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
