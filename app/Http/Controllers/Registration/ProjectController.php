<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\StoreProjectRequest;
use App\Models\Group;
use App\Models\Product;
use App\Models\Project;
use App\Services\CommonService;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
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

        $groups = Cache::remember('groups_data', 3600, function () {
            return Group::select('id', 'name_en', 'name_ur')->get();
        });
        $companies = Cache::remember('companies_data', 3600, function () {
            return \App\Models\Company::select('id', 'name_en', 'name_ur')->get();
        });

        return view('registration.projects.create', compact('groups', 'companies'));
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
        $groups = Cache::remember('groups_data', 3600, function () {
            return Group::select('id', 'name_en', 'name_ur')->get();
        });
        $companies = Cache::remember('companies_data', 3600, function () {
            return \App\Models\Company::select('id', 'name_en', 'name_ur')->get();
        });
        return view('registration.projects.edit', compact('project', 'groups', 'companies'));
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

            // Update project first
            $project->update($data);

            /*
         * Get all Direct products for this project
         * where sub_head_id = 7 and status is not Booked.
         */
            $products = Product::where('project_id', $project->id)
                ->where('sub_head_id', 7)
                ->where('status', '!=', 'Booked')
                ->where('type', 'Direct')
                ->get();

            /*
         * Project residential rate
         * -> Product amount_in_pkr
         */
            $residentialRate = (float) ($project->residential_rate ?? 0);

            /*
         * Project square feet.
         *
         * IMPORTANT:
         * Make sure this is the same project square-feet
         * value returned by your getProjectSquareFeet() method.
         */
            $projectSquareFeet = (float) ($project->square_feet ?? 0);

            /*
         * Same facing percentages as your JavaScript.
         */
            $facingPercentages = [
                1  => 15,
                2  => 25,
                3  => 25,
                4  => 25,
                5  => 25,
                6  => 10,
                7  => 20,
                8  => 20,
                9  => 20,
                10 => 20,
                11 => 10,
                12 => 10,
                13 => 25,
                14 => 0,
                15 => 10,
                16 => 20,
                17 => 25,
                18 => 10,
                19 => 20,
                20 => 20,
                21 => 25,
                22 => 20,
                23 => 20,
                24 => 25,
                25 => 25,
            ];

            /*
         * Only calculate if project square feet
         * and residential rate are available.
         */
            if ($projectSquareFeet > 0 && $residentialRate > 0) {

                foreach ($products as $product) {

                    $kanal = (float) ($product->kanal ?? 0);
                    $marla = (float) ($product->marla ?? 0);
                    $squareFeet = (float) ($product->square_feet ?? 0);

                    /*
                 * Same as JS:
                 *
                 * totalSqFt =
                 *     (kanal * 20 * projectSquareFeet)
                 *     + (marla * projectSquareFeet)
                 *     + squareFeet
                 */
                    $totalSqFt =
                        ($kanal * 20 * $projectSquareFeet)
                        + ($marla * $projectSquareFeet)
                        + $squareFeet;

                    /*
                 * Same as JS:
                 *
                 * sumAns =
                 *     (totalSqFt / projectSquareFeet)
                 *     * rate
                 *
                 * Here rate = project residential_rate.
                 */
                    $totalAmount =
                        ($totalSqFt / $projectSquareFeet)
                        * $residentialRate;

                    /*
                 * Apply front/facing percentage.
                 */
                    $frontId = (int) ($product->front_id ?? 0);

                    if (isset($facingPercentages[$frontId])) {

                        $percentage = $facingPercentages[$frontId];

                        $totalAmount +=
                            $totalAmount * ($percentage / 100);
                    }

                    /*
                 * Update the EXISTING product.
                 *
                 * Project residential_rate
                 *       ↓
                 * product.amount_in_pkr
                 *
                 * Calculated total
                 *       ↓
                 * product.total_amount
                 */
                    $product->amount_in_pkr = $residentialRate;
                    $product->total_amount = round($totalAmount, 2);

                    $product->save();
                }
            }

            return redirect()
                ->route('projects.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {

            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'error',
                    __('messages.error-title') . ' ' . $e->getMessage()
                );
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
