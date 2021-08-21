<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ResearchCategory;
use App\Http\Controllers\Controller;

class ResearchCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $researchcategories = ResearchCategory::all();
        return view('admin.maintenances.researchcategory.index', compact('researchcategories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.researchcategory.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);

        ResearchCategory::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchcategory')->with('success', 'Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(ResearchCategory $research_category)
    {
        return view('admin.maintenances.researchcategory.edit', compact('research_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResearchCategory $research_category)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $research_category->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchcategory')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchCategory $research_category)
    {
        $research_category->delete();
        return redirect()->route('admin.maintenances.researchcategory')->with('success','Deleted successfully.');
    }
}
