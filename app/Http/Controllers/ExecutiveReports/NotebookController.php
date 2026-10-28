<?php

namespace App\Http\Controllers\ExecutiveReports;

use App\Http\Controllers\Controller;
use App\Models\Notebook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotebookController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $notebooks = Notebook::where('user_id', auth()->id())
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $query) use ($search) {
                    $query->where('name_en', 'like', "%{$search}%")
                        ->orWhere('name_ur', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        return view('executive-reports.notebooks.index', compact('notebooks', 'search'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());
        $validated['user_id'] = auth()->id();

        Notebook::create($validated);

        return redirect()->route('executive-reports.notebooks.index')
            ->with('success', __('messages.notebook_saved'));
    }

    public function update(Request $request, int $notebook): RedirectResponse
    {
        $validated = $request->validate($this->validationRules());

        $ownedNotebook = Notebook::where('user_id', auth()->id())->findOrFail($notebook);
        $ownedNotebook->update($validated);

        return redirect()->route('executive-reports.notebooks.index')
            ->with('success', __('messages.notebook_updated'));
    }

    public function destroy(int $notebook): RedirectResponse
    {
        Notebook::where('user_id', auth()->id())
            ->findOrFail($notebook)
            ->delete();

        return redirect()->route('executive-reports.notebooks.index')
            ->with('success', __('messages.notebook_deleted'));
    }

    private function validationRules(): array
    {
        return [
            'name_en' => ['required', 'string', 'max:255'],
            'name_ur' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'color' => ['required', 'regex:/^#[0-9A-Fa-f]{6}$/'],
        ];
    }
}
