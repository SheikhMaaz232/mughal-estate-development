<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\DetailAccountRequest;
use App\Jobs\SaveDetailAccountJob;
use App\Models\ControlHead;
use App\Models\DetailAccount;
use App\Models\MainHead;
use App\Models\Project;
use App\Models\SubHead;
use App\Models\SubSubHead;
use App\Models\SubSubSubHead;
use App\Services\DetailAccountService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DetailAccountController extends Controller
{
    protected $detailAccountService;

    public function __construct(DetailAccountService $detailAccountService)
    {
        $this->detailAccountService = $detailAccountService;
    }

    /**
     * Display a listing of detailAccounts.
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $search = $request->input('search');

        $detailAccountListings = DetailAccount::with('mainHead', 'controlHead', 'subHead', 'subSubHead', 'subSubSubHead', 'party')->search($search, $filters)->latest()->paginate(10)->appends(request()->input());

        return view('registration.detail_account.index', compact('detailAccountListings', 'search'));
    }

    /**
     * Display accounts tree view (bilingual).
     */
    // public function tree()
    // {
    //     $mainHeads = MainHead::orderBy('id')->get();

    //     $accountsTree = [];

    //     foreach ($mainHeads as $mh) {
    //         $mhItem = [
    //             'id' => $mh->id,
    //             'name_en' => $mh->name_en,
    //             'name_ur' => $mh->name_ur,
    //             'control_heads' => []
    //         ];

    //         $controlHeads = ControlHead::where('main_head_id', $mh->id)->get();
    //         foreach ($controlHeads as $ch) {
    //             $chItem = [
    //                 'id' => $ch->id,
    //                 'name_en' => $ch->name_en,
    //                 'name_ur' => $ch->name_ur,
    //                 'sub_heads' => []
    //             ];

    //             $subHeads = SubHead::where('control_head_id', $ch->id)->get();
    //             foreach ($subHeads as $sh) {
    //                 $shItem = [
    //                     'id' => $sh->id,
    //                     'name_en' => $sh->name_en,
    //                     'name_ur' => $sh->name_ur,
    //                     'sub_sub_heads' => []
    //                 ];

    //                 $subSubHeads = SubSubHead::where('sub_head_id', $sh->id)->get();
    //                 foreach ($subSubHeads as $ssh) {
    //                     $sshItem = [
    //                         'id' => $ssh->id,
    //                         'name_en' => $ssh->name_en,
    //                         'name_ur' => $ssh->name_ur,
    //                         'sub_sub_sub_heads' => []
    //                     ];

    //                     $subSubSubHeads = SubSubSubHead::where('sub_sub_head_id', $ssh->id)->get();
    //                     foreach ($subSubSubHeads as $sssh) {
    //                         $ssshItem = [
    //                             'id' => $sssh->id,
    //                             'name_en' => $sssh->name_en,
    //                             'name_ur' => $sssh->name_ur,
    //                             'detail_accounts' => []
    //                         ];

    //                         $detailAccounts = DetailAccount::where('sub_sub_sub_head_id', $sssh->id)->get();
    //                         foreach ($detailAccounts as $da) {
    //                             $ssshItem['detail_accounts'][] = [
    //                                 'id' => $da->id,
    //                                 'name_en' => $da->name_en,
    //                                 'name_ur' => $da->name_ur,
    //                             ];
    //                         }

    //                         $sshItem['sub_sub_sub_heads'][] = $ssshItem;
    //                     }

    //                     // detail accounts directly under sub_sub_head (no sub_sub_sub_head)
    //                     $directDA_SSH = DetailAccount::where('sub_sub_head_id', $ssh->id)
    //                         ->whereNull('sub_sub_sub_head_id')
    //                         ->get();
    //                     foreach ($directDA_SSH as $da) {
    //                         $sshItem['sub_sub_sub_heads'][] = [
    //                             'id' => null,
    //                             'name_en' => null,
    //                             'name_ur' => null,
    //                             'detail_accounts' => [
    //                                 [
    //                                     'id' => $da->id,
    //                                     'name_en' => $da->name_en,
    //                                     'name_ur' => $da->name_ur,
    //                                 ]
    //                             ]
    //                         ];
    //                     }

    //                     $shItem['sub_sub_heads'][] = $sshItem;
    //                 }

    //                 // detail accounts directly under sub_head
    //                 $directDA_SH = DetailAccount::where('sub_head_id', $sh->id)
    //                     ->whereNull('sub_sub_head_id')
    //                     ->get();
    //                 foreach ($directDA_SH as $da) {
    //                     $shItem['sub_sub_heads'][] = [
    //                         'id' => null,
    //                         'name_en' => null,
    //                         'name_ur' => null,
    //                         'sub_sub_sub_heads' => [],
    //                         'detail_accounts' => [
    //                             [
    //                                 'id' => $da->id,
    //                                 'name_en' => $da->name_en,
    //                                 'name_ur' => $da->name_ur,
    //                             ]
    //                         ]
    //                     ];
    //                 }

    //                 $chItem['sub_heads'][] = $shItem;
    //             }

    //             // detail accounts directly under control head
    //             $directDA_CH = DetailAccount::where('control_head_id', $ch->id)
    //                 ->whereNull('sub_head_id')
    //                 ->get();
    //             foreach ($directDA_CH as $da) {
    //                 $chItem['sub_heads'][] = [
    //                     'id' => null,
    //                     'name_en' => null,
    //                     'name_ur' => null,
    //                     'sub_sub_heads' => [],
    //                     'detail_accounts' => [
    //                         [
    //                             'id' => $da->id,
    //                             'name_en' => $da->name_en,
    //                             'name_ur' => $da->name_ur,
    //                         ]
    //                     ]
    //                 ];
    //             }

    //             $mhItem['control_heads'][] = $chItem;
    //         }

    //         // detail accounts directly under main head
    //         $directDA_MH = DetailAccount::where('main_head_id', $mh->id)
    //             ->whereNull('control_head_id')
    //             ->get();
    //         foreach ($directDA_MH as $da) {
    //             $mhItem['control_heads'][] = [
    //                 'id' => null,
    //                 'name_en' => null,
    //                 'name_ur' => null,
    //                 'sub_heads' => [],
    //                 'detail_accounts' => [
    //                     [
    //                         'id' => $da->id,
    //                         'name_en' => $da->name_en,
    //                         'name_ur' => $da->name_ur,
    //                     ]
    //                 ]
    //             ];
    //         }

    //         $accountsTree[] = $mhItem;
    //     }

    //     return view('registration.detail_account.tree', compact('accountsTree'));
    // }

    public function tree(Request $request)
    {
        $projects = Project::orderBy('name_en')->get();

        $projectId = $request->project_id;

        // Don't load tree on first page load
        if (!$request->has('project_id')) {

            return view(
                'registration.detail_account.tree',
                [
                    'projects'      => $projects,
                    'accountsTree'  => [],
                    'mainHeadsTree' => null,
                    'projectId'     => null
                ]
            );
        }

        $query = MainHead::query();

        // Only include Main Heads that have matching SubSubSubHead
        if (!empty($projectId)) {

            $query->whereHas(
                'controlHeads.subHeads.subSubHeads.subSubSubHeads',
                function ($q) use ($projectId) {

                    $q->where('project_id', $projectId);
                }
            );
        }

        $mainHeadsTree = $query
            ->with([

                'detailAccounts',

                'controlHeads.detailAccounts',

                'controlHeads.subHeads.detailAccounts',

                'controlHeads.subHeads.subSubHeads.detailAccounts',

                'controlHeads.subHeads.subSubHeads.subSubSubHeads' => function ($q) use ($projectId) {

                    if (!empty($projectId)) {

                        $q->where('project_id', $projectId);
                    }
                },

                'controlHeads.subHeads.subSubHeads.subSubSubHeads.detailAccounts'

            ])
            ->orderBy('id')
            ->paginate(10);

        $accountsTree = [];

        foreach ($mainHeadsTree as $mh) {

            $mhItem = [
                'id' => $mh->id,
                'name_en' => $mh->name_en,
                'name_ur' => $mh->name_ur,
                'control_heads' => []
            ];

            foreach ($mh->controlHeads as $ch) {

                $chItem = [
                    'id' => $ch->id,
                    'name_en' => $ch->name_en,
                    'name_ur' => $ch->name_ur,
                    'sub_heads' => []
                ];

                foreach ($ch->subHeads as $sh) {

                    $shItem = [
                        'id' => $sh->id,
                        'name_en' => $sh->name_en,
                        'name_ur' => $sh->name_ur,
                        'sub_sub_heads' => []
                    ];

                    foreach ($sh->subSubHeads as $ssh) {

                        $sshItem = [
                            'id' => $ssh->id,
                            'name_en' => $ssh->name_en,
                            'name_ur' => $ssh->name_ur,
                            'sub_sub_sub_heads' => [],
                            'detail_accounts' => []
                        ];

                        foreach ($ssh->subSubSubHeads as $sssh) {

                            $ssshItem = [

                                'id' => $sssh->id,
                                'name_en' => $sssh->name_en,
                                'name_ur' => $sssh->name_ur,
                                'detail_accounts' => []

                            ];

                            foreach ($sssh->detailAccounts as $da) {

                                $ssshItem['detail_accounts'][] = [

                                    'id' => $da->id,
                                    'name_en' => $da->name_en,
                                    'name_ur' => $da->name_ur

                                ];
                            }

                            $sshItem['sub_sub_sub_heads'][] = $ssshItem;
                        }

                        foreach ($ssh->detailAccounts as $da) {

                            $sshItem['detail_accounts'][] = [

                                'id' => $da->id,
                                'name_en' => $da->name_en,
                                'name_ur' => $da->name_ur

                            ];
                        }

                        $shItem['sub_sub_heads'][] = $sshItem;
                    }

                    foreach ($sh->detailAccounts as $da) {

                        $shItem['sub_sub_heads'][] = [

                            'id' => null,
                            'name_en' => null,
                            'name_ur' => null,
                            'sub_sub_sub_heads' => [],
                            'detail_accounts' => [[

                                'id' => $da->id,
                                'name_en' => $da->name_en,
                                'name_ur' => $da->name_ur

                            ]]

                        ];
                    }

                    $chItem['sub_heads'][] = $shItem;
                }

                foreach ($ch->detailAccounts as $da) {

                    $chItem['sub_heads'][] = [

                        'id' => null,
                        'name_en' => null,
                        'name_ur' => null,
                        'sub_sub_heads' => [],
                        'detail_accounts' => [[

                            'id' => $da->id,
                            'name_en' => $da->name_en,
                            'name_ur' => $da->name_ur

                        ]]

                    ];
                }

                $mhItem['control_heads'][] = $chItem;
            }

            foreach ($mh->detailAccounts as $da) {

                $mhItem['control_heads'][] = [

                    'id' => null,
                    'name_en' => null,
                    'name_ur' => null,
                    'sub_heads' => [],
                    'detail_accounts' => [[

                        'id' => $da->id,
                        'name_en' => $da->name_en,
                        'name_ur' => $da->name_ur

                    ]]

                ];
            }

            $accountsTree[] = $mhItem;
        }

        return view('registration.detail_account.tree', compact( 'projects','projectId', 'accountsTree', 'mainHeadsTree'));
    }

    /**
     * Show the form for creating a new Sub-Sub-Head.
     */
    public function create()
    {
        return view('registration.detail_account.create');
    }

    /**
     * Store a newly created Sub-Sub-Head in storage.
     */
    public function store(DetailAccountRequest $request)
    {
        try {
            $data = $request->all();
            SaveDetailAccountJob::dispatch($data);

            return redirect()
                ->route('detail-accounts.index')
                ->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('DetailAccount store failed: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('detail-accounts.index')
                ->with('error', __('messages.record-not-saved'));
        }
    }

    /**
     * Show the form for editing the specified Sub-Sub-Head.
     */
    public function edit($id)
    {
        try {
            $detailAccount = $this->detailAccountService->getById($id);
            $controlHeads = ControlHead::where('main_head_id', $detailAccount->main_head_id)->get();
            $subHeads = SubHead::where('control_head_id', $detailAccount->control_head_id)->get();
            $subSubHeads = SubSubHead::where('sub_head_id', $detailAccount->sub_head_id)->get();
            $subSubSubHeads = SubSubSubHead::where('sub_sub_head_id', $detailAccount->sub_sub_head_id)->get();


            return view('registration.detail_account.edit', compact('detailAccount', 'controlHeads', 'subHeads', 'subSubHeads', 'subSubSubHeads'));
        } catch (\Exception $e) {
            return redirect()->route('detail-accounts.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Update the specified Sub-Sub-Head in storage.
     */

    public function update(DetailAccountRequest $request, $id)
    {
        try {
            $data = $request->all();
            $data['id'] = $id; // attach the id of the record being updated

            // Dispatch the job to queue
            \App\Jobs\UpdateDetailAccountJob::dispatch($data);

            return redirect()
                ->route('detail-accounts.index')
                ->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            Log::error('DetailAccount update failed: ' . $e->getMessage(), [
                'id'    => $id,
                'data'  => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('messages.unexpected-error'));
        }
    }


    /**
     * Remove the specified Sub-Sub-Head from storage.
     */
    public function destroy($id)
    {
        try {
            $this->detailAccountService->delete($id);
            return redirect()->route('detail-accounts.index')->with('success', __('messages.record-deleted'));
        } catch (\Exception $e) {
            return redirect()->route('detail-accounts.index')->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Return Sub-Sub-Heads list for given Sub-Head (used in dependent dropdown).
     */
    public function getSubSubSubAccountForSubSubHead($subSubHead)
    {
        try {
            $subSubAccounts = $this->detailAccountService->getSubSubSubHeadsForSubSubHead($subSubHead);
            if ($subSubAccounts) {
                return response()->json(['status' => 'success', 'data' => $subSubAccounts]);
            }
            return response()->json(['status' => 'fail', 'data' => []]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'fail', 'data' => []]);
        }
    }

    public function fetchSubSubSubHead(Request $request)
    {
        $field = app()->getLocale() === 'ur'
            ? 'name_ur'
            : 'name_en';

        $subSubSubHeads = SubSubSubHead::query()
            ->when($request->search, function ($query) use ($request, $field) {
                $query->where($field, 'like', '%' . $request->search . '%');
            })
            ->paginate(20);

        return response()->json([
            'results' => $subSubSubHeads->map(function ($subSubSubHead) use ($field) {
                return [
                    'id' => $subSubSubHead->id,
                    'text' => $subSubSubHead->$field,
                ];
            }),
            'pagination' => [
                'more' => $subSubSubHeads->hasMorePages(),
            ],
        ]);
    }

    public function fetchSubSubHead(Request $request)
    {
        $field = app()->getLocale() === 'ur'
            ? 'name_ur'
            : 'name_en';

        $subSubHeads = SubSubHead::query()
            ->when($request->search, function ($query) use ($request, $field) {
                $query->where($field, 'like', '%' . $request->search . '%');
            })
            ->paginate(20);

        return response()->json([
            'results' => $subSubHeads->map(function ($subSubHead) use ($field) {
                return [
                    'id' => $subSubHead->id,
                    'text' => $subSubHead->$field,
                ];
            }),
            'pagination' => [
                'more' => $subSubHeads->hasMorePages(),
            ],
        ]);
    }
}
