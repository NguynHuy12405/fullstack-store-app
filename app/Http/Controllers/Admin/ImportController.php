<?php

namespace App\Http\Controllers\Admin;

use App\Models\Import;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreImportRequest;
use App\Http\Requests\UpdateImportRequest;
use App\Models\Color;
use App\Models\Variant;
use Illuminate\Http\Request;

class ImportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Nhập hàng';
        $viewData['subtitle'] = 'Quản lý nhập hàng';

        $imports = Import::query()->filter($request->all())->with(['user', 'importDetails.variant.product'])->paginate(10)->withQueryString();

        $counts = Import::selectRaw("
            count(*) as total_count,
            sum(total_price) as total_price_count,
            (select sum(quantity) from import_details) as total_items_count
        ")->first();

        return view('admin.import.index', compact('viewData', 'imports', 'counts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $viewData = [];
        $viewData['title'] = 'Admin - Nhập hàng';
        $viewData['subtitle'] = 'Tạo phiếu nhập hàng';

        $variants = Variant::query()->filter($request->all())->with('product')->paginate(10)->withQueryString();

        $colors = Color::all();

        return view('admin.import.create', compact('viewData', 'variants', 'colors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreImportRequest $request)
    {
        $import = Import::create($request->validated());
        $import->importDetails()->createMany($request->variants);
        return redirect()->route('admin.import.create')->with('success', 'Tạo phiếu nhập thành công!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Import $import)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Import $import)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Import $import)
    {
        //
    }
}
