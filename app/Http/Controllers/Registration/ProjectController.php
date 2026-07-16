<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Http\Requests\Registration\StoreProjectRequest;
use App\Services\CommonService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    protected $commonService;

    public function __construct(CommonService $commonService)
    {
        $this->commonService = $commonService;
    }

    /**
     * Display a listing of projects with optional search filter
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $projectsListing = Project::select('id', 'name_en', 'name_ur', 'project_map', 'square_feet', 'total_area')->search($search, $request)->latest()->paginate(15);

        return view('registration.projects.index', compact('projectsListing', 'search'));
    }

    /**
     * Show form to create a new project
     */
    public function create()
    {
        return view('registration.projects.create');
    }

    /**
     * Store a newly created project
     */
    public function store(StoreProjectRequest $request)
    {
        try {
            $data = $request->all();
            app(ProjectService::class)->create($data, $request->file('project_map'));

            return redirect()->route('projects.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.error-title') . ' ' . $e->getMessage());
        }
    }

    /**
     * Show form to edit an existing project
     */
    public function edit(Project $project)
    {
        return view('registration.projects.edit', compact('project'));
    }

    /**
     * Update an existing project
     */
    public function update(StoreProjectRequest $request, Project $project)
    {

        try {
            $data = $request->validated();
            if ($project->project_map && Storage::exists($project->project_map)) {
                Storage::delete($project->project_map);
            }

            if ($request->hasFile('project_map')) {
                $data['project_map'] = $this->commonService->uploadImage(
                    $request->file('project_map'),
                    'projects'
                );
            }

            $project->update($data);

            return redirect()->route('projects.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.error-title') . ' ' . $e->getMessage());
        }
    }

    /**
     * Display the specified Party details with related Bank Accounts.
     *
     * @param  \App\Models\Party  $party
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Project $project)
    {
        try {
            // Return the Blade view with Party and Bank details
            return view('registration.projects.show', compact('project'));
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Delete a project
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return redirect()->route('projects.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('messages.error-title') . ' ' . $e->getMessage());
        }
    }
}
