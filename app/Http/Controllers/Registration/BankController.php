<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreBankRequest;
use App\Http\Requests\Registration\UpdateBankRequest;
use App\Models\Bank;

class BankController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banksListing = Bank::latest()->paginate(10);
        return view('registration.banks.index', compact('banksListing'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('registration.banks.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBankRequest $request)
    {
        Bank::create($request->all());

        return redirect()->route('banks.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        return view('registration.banks.show', compact('bank'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank)
    {
        return view('registration.banks.edit', compact('bank'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateBankRequest $request, Bank $bank)
    {
        $bank->update($request->all());

        return redirect()->route('banks.index')
            ->with('success', __('messages.record-saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Bank $bank)
    {
        $bank->delete();

        return redirect()->route('banks.index')
            ->with('success', __('messages.record-saved'));
    }
}
