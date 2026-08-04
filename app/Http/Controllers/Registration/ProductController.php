<?php

namespace App\Http\Controllers\Registration;

use App\Http\Controllers\Controller;
use App\Http\Requests\Registration\ProductRequest;
use App\Models\ControlHead;
use App\Models\Facing;
use App\Models\MainHead;
use App\Models\Product;
use App\Models\Project;
use App\Models\RoadCategory;
use App\Models\SubHead;
use App\Models\SubSubHead;
use App\Models\SubSubSubHead;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    protected $productService;

    // Constructor to inject ProductService
    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    private function getMasterData()
    {
        return [
            'mainHeads' => Cache::remember('main_heads_data', 3600, fn() =>
            MainHead::select('id', 'name_en', 'name_ur')->get()),

            'searchControlHeads' => Cache::remember('control_heads_data', 3600, fn() =>
            ControlHead::select('id', 'name_en', 'name_ur')->get()),

            'searchSubHeads' => Cache::remember('sub_heads_data', 3600, fn() =>
            SubHead::select('id', 'name_en', 'name_ur')->get()),

            'searchSubSubHeads' => Cache::remember('sub_sub_heads_data', 3600, fn() =>
            SubSubHead::select('id', 'name_en', 'name_ur')->get()),

            'searchSubSubSubHeads' => Cache::remember('sub_sub_sub_heads_data', 3600, fn() =>
            SubSubSubHead::select('id', 'name_en', 'name_ur')->get()),

            'projects' => Cache::remember('projects_data', 3600, fn() =>
            Project::select('id', 'name_en', 'name_ur')->get()),

            'roadCategories' => Cache::remember('road_categories_data', 3600, fn() =>
            RoadCategory::select('id', 'title_en', 'title_ur')->get()),

            'facings' => Cache::remember('facings_data', 3600, fn() =>
            Facing::select('id', 'name_en', 'name_ur')->get()),

        ];
    }

    /**
     * Display a listing of all products.
     */
    public function index(Request $request)
    {
        $search = $request->all();

        $products = Product::select(
            'id',
            'project_id',
            'main_head_id',
            'control_head_id',
            'unit_no',
            'sub_head_id',
            'sub_sub_head_id',
            'sub_sub_sub_head_id',
            'name_en',
            'name_ur',
            'status',
            'type'
        )->with('project', 'mainHead', 'controlHead', 'subHead', 'subSubHead', 'subSubSubHead')->search($search)->latest()->paginate(10)->appends(request()->input());


        return view(
            'registration.products.index',
            array_merge(
                [
                    'products' => $products,
                    'search' => $search,
                ],
                $this->getMasterData()
            )
        );
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        return view('registration.products.create', $this->getMasterData());
    }

    /**
     * Store a newly created product in the database.
     *
     * @param ProductRequest $request - Form request containing product data
     */
    public function store(ProductRequest $request)
    {
        try {
            $data = $request->all();
            app(ProductService::class)->create($data, $request->file('image'));
            app(ProductService::class)->createFromProduct($data);

            return redirect()->route('products.index')->with('success', __('messages.record-saved'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Show the form for editing an existing product.
     *
     * @param int $id - Product ID
     */
    public function edit($id)
    {
        $product = $this->productService->getById($id);
        $controlHeads = ControlHead::where('main_head_id', $product->main_head_id)->get();
        $subHeads = SubHead::where('control_head_id', $product->control_head_id)->get();
        $subSubHeads = SubSubHead::where('sub_head_id', $product->sub_head_id)->get();
        $subSubSubHeads = SubSubSubHead::where('sub_sub_head_id', $product->sub_sub_head_id)->get();


        return view(
            'registration.products.edit',
            array_merge(
                [
                    'product' => $product,
                    'controlHeads' => $controlHeads,
                    'subHeads' => $subHeads,
                    'subSubHeads' => $subSubHeads,
                    'subSubSubHeads' => $subSubSubHeads,
                ],
                $this->getMasterData()
            )
        );
    }

    /**
     * Update the specified product in the database.
     *
     * @param ProductRequest $request - Form request containing updated product data
     * @param int $id - Product ID
     */
    public function update(Request $request, $id)
    {
        try {

            $data = $request->all();
            $image = $request->file('image');

            app(ProductService::class)->updateFromProduct($data, $id);
            $this->productService->update($id, $data, $image);

            return redirect()->route('products.index')->with('success', __('messages.record-updated'));
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Display the specified Product details.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Product $product)
    {
        try {
            // Return the Blade view with Product
            return view('registration.products.show', compact('product'));
        } catch (\Exception $e) {
            // Redirect back with error message
            return redirect()->back()->with('error', __('messages.unexpected-error'));
        }
    }

    /**
     * Remove the specified product from the database.
     *
     * @param int $id - Product ID
     */
    public function destroy($id)
    {
        $this->productService->delete($id);

        return redirect()->route('products.index')->with('success', __('messages.record-deleted'));
    }

    public function updateStatus(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Verified,Unverified'
        ]);

        $product->status = $request->status;
        $product->save(); // 🔹 Only updates existing row

        return redirect()->route('products.index')
            ->with('success',  __('messages.product-status-updated'));
    }


    /**
     * Generate and return the next available detail account code based on provided code.
     *
     * @param string $code - Base code for generating detail account code
     */
    public function getMaxDetailAccountCode($code)
    {
        $detailAccountCode = $this->productService->generateDetailAccountCode($code);
        return response()->json(['status' => 'success', 'account_code' => $detailAccountCode]);
    }

    /**
     * Generate and return the next available detail account code based on provided code.
     *
     * @param string $code - Base code for generating detail account code
     */
    public function getProjectSquareFeet($projectId)
    {

        $project = $this->productService->projectSquareFeet($projectId);

        return response()->json([
            'status'      => 'success',
            'squareFeet'  => $project->square_feet ?? null,
            'companyId'   => $project->company_id ?? null,
        ]);
    }
}
