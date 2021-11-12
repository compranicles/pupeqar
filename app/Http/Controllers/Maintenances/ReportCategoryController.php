<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\ReportType;
use Illuminate\Support\Facades\Schema;
use App\Models\Maintenance\ReportColumn;
use App\Models\Maintenance\ReportCategory;

class ReportCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ReportType $report_type)
    {
        echo 'lol';
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ReportCategory $report_category)
    {
        $report_columns = ReportColumn::where('report_category_id', $report_category->id)->orderBy('order')->get();
        return view('maintenances.reports.categories.show', compact('report_category', 'report_columns'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportCategory $report_category)
    {
        $report_category->update([
            'name' => $request->name
        ]);

        return redirect()->route('report-categories.show', $report_category->id)->with('success', 'Research Table renamed sucessfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getTableColumns($table){
        return Schema::getColumnListing($table);
    }
}
