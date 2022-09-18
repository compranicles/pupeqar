<?php

namespace App\Http\Controllers\Maintenances;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    DocumentDescription,
    FormBuilder\ResearchField,
    FormBuilder\ResearchForm,
    Maintenance\ReportCategory,
};

class DocumentDescriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $report_categories = ReportCategory::where('is_active', 1)->select('id', 'name')->get();
        return view('maintenances.document-description.add', compact('report_categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->input('report_category_id'));
        if ($request->input('is_active') == "on") {
            $active = 1;
        }
        else {
            $active = 0;
        }

        DocumentDescription::create([
            'name' => $request->input('name'),
            // 'purpose' => $request->input('purpose'),
            'report_category_id' => $request->input('report_category_id'),
            'is_active' => $active
        ]);

        $report_category = ReportCategory::where('id', $request->input('report_category_id'))->select('name')->first();
        $report_category_id = $request->input('report_category_id');
        if (($report_category_id == 1) || ($report_category_id == 2) ||
        ($report_category_id == 3) || ($report_category_id == 4) || 
        ($report_category_id == 5) || ($report_category_id == 6) ||
        ($report_category_id == 7)) {
            return redirect()->route('research-forms.index')->with('success', 'Document description in '.$report_category->name.' has been created.');
        }
        elseif ($report_category_id == 8) {
            return redirect()->route('invention-forms.index')->with('success', 'Document description in '.$report_category->name.' has been created.');
        }
        elseif (($report_category_id == 9) || ($report_category_id == 10) ||
        ($report_category_id == 11) || ($report_category_id == 12) ||
        ($report_category_id == 13) || ($report_category_id == 14) ||
        ($report_category_id == 22) || ($report_category_id == 37) ||
        ($report_category_id == 38) || ($report_category_id == 39)) {
            return redirect()->route('extension-program-forms.index')->with('success', 'Document description in '.$report_category->name.' has been created.');
        }
        elseif (($report_category_id == 15) || ($report_category_id == 16) ||
        ($report_category_id == 18) || ($report_category_id == 19) ||
        ($report_category_id == 20) || ($report_category_id == 21) ||
        ($report_category_id == 23)) {
            return redirect()->route('academic-module-forms.index')->with('success', 'Document description in '.$report_category->name.' has been created.');
        }
        elseif ($report_category_id == 17 || $report_category_id == 29 ||
        $report_category_id == 30 || $report_category_id == 31 ||
        $report_category_id == 32 || $report_category_id == 33) {
            return redirect()->route('ipcr-forms.index')->with('success', 'Document description in '.$report_category->name.' has been created.');
        }
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
    public function edit(DocumentDescription $document_description)
    {
        $report_category = DocumentDescription::join('report_categories', 'report_categories.id', 'document_descriptions.report_category_id')
                            ->where('report_categories.id', $document_description->report_category_id)
                            ->select('report_categories.name')->first();
                            // dd($report_category);
        return view('maintenances.document-description.edit', compact('document_description', 'report_category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DocumentDescription $document_description)
    {
        $report_category = DocumentDescription::join('report_categories', 'report_categories.id', 'document_descriptions.report_category_id')
                            ->where('report_categories.id', $document_description->report_category_id)
                            ->select('report_categories.name')->first();
        if ($request->input('is_active') == "on") {
            $active = 1;
        }
        else {
            $active = 0;
        }
        $document_description->update([
            'name' => $request->input('name'),
            'purpose' => $request->input('purpose'),
            'is_active' => $active
        ]);

        if (($document_description->report_category_id == 1) || ($document_description->report_category_id == 2) ||
        ($document_description->report_category_id == 3) || ($document_description->report_category_id == 4) || 
        ($document_description->report_category_id == 5) || ($document_description->report_category_id == 6) ||
        ($document_description->report_category_id == 7)) {
            return redirect()->route('research-forms.index')->with('success', 'Document description in '.$report_category->name.' has been updated.');
        }
        elseif ($document_description->report_category_id == 8) {
            return redirect()->route('invention-forms.index')->with('success', 'Document description in '.$report_category->name.' has been updated.');
        }
        elseif (($document_description->report_category_id == 9) || ($document_description->report_category_id == 10) ||
        ($document_description->report_category_id == 11) || ($document_description->report_category_id == 12) ||
        ($document_description->report_category_id == 13) || ($document_description->report_category_id == 14) ||
        ($document_description->report_category_id == 22)) {
            return redirect()->route('extension-program-forms.index')->with('success', 'Document description in '.$report_category->name.' has been updated.');
        }
        elseif (($document_description->report_category_id == 15) || ($document_description->report_category_id == 16) ||
        ($document_description->report_category_id == 18) || ($document_description->report_category_id == 19) ||
        ($document_description->report_category_id == 20) || ($document_description->report_category_id == 21) ||
        ($document_description->report_category_id == 23)) {
            return redirect()->route('academic-module-forms.index')->with('success', 'Document description in '.$report_category->name.' has been updated.');
        }
        elseif ($document_description->report_category_id == 17) {
            return redirect()->route('ipcr-forms.index')->with('success', 'Document description in '.$report_category->name.' has been updated.');
        }
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

    //
    public function getDescriptionsByReportCategory($report_category_id) {
        return DocumentDescription::where('is_active', 1)->where('report_category_id', $report_category_id)->get();  
    }

    public function isActive($report_category_id, $description_id, $is_active) {
        DocumentDescription::where('report_category_id', $report_category_id)
            ->where('id', $description_id)
            ->update([
            'is_active' => $is_active
        ]);

        return true;
    }
}
