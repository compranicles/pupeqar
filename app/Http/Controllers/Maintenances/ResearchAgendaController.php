<?php

namespace App\Http\Controllers\Maintenances;

use Illuminate\Http\Request;
use App\Models\ResearchAgenda;
use App\Http\Controllers\Controller;

class ResearchAgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $researchagendas = ResearchAgenda::all();
        return view('admin.maintenances.researchagenda.index', compact('researchagendas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.maintenances.researchagenda.create');
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

        ResearchAgenda::create([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchagenda')->with('success', 'Added successfully');
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
    public function edit(ResearchAgenda $research_agenda)
    {
        return view('admin.maintenances.researchagenda.edit', compact('research_agenda'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ResearchAgenda $research_agenda)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $research_agenda->update([
            'name' => $request->input('name')
        ]);

        return redirect()->route('admin.maintenances.researchagenda')->with('success', 'Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ResearchAgenda $research_agenda)
    {
        $research_agenda->delete();
        return redirect()->route('admin.maintenances.researchagenda')->with('success','Deleted successfully.');
    }
}
