<?php

namespace App\Http\Controllers\Research;

use App\Models\Research;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ResearchCitation;
use App\Models\ResearchComplete;
use App\Models\ResearchDocument;
use App\Models\ResearchCopyright;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Models\ResearchPublication;
use App\Models\ResearchUtilization;
use App\Http\Controllers\Controller;
use App\Models\ResearchPresentation;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
use App\Models\FormBuilder\ResearchForm;
use App\Models\FormBuilder\ResearchField;
use App\Models\FormBuilder\DropdownOption;


class ResearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $this->authorize('viewAny', Research::class);

        $researchStatus = DropdownOption::where('dropdown_id', 7)->get();
        $researches = Research::where('user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->orderBy('research.updated_at', 'desc')->get();
        return view('research.index', compact('researches', 'researchStatus'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id(1)");

        $colleges = College::all();
        return view('research.create', compact('researchFields', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $request->validate([
            'classification' => 'required',
            'status' => 'required',
            'category' => 'required',
            'agenda' => 'required',
            'title' => 'required',
            // 'researcher' => 'required',
            'keywords' => 'required',
            // 'nature_of_involvement' => 'required',
            'research_type' => 'required',
            'funding_type' => 'required',
            'currency_funding_amount' => 'required',
            'funding_amount' => 'numeric',
            'funding_agency' => 'required_if:funding_type,23',
            // 'start_date' => 'required_unless:status,32|date',
            // 'target_date' => 'required_unless: status, 28|after_or_equal: start_date|date',
            // 'completion_date' => 'date|after_or_equal:start_date|required_if:status, 28',
            'college_id' => 'required',
            'department_id' => 'required',
            // 'description' => 'required',
        ]);

        $departmentIni = '';
        $classIni = '';
        $catIni = '';
        $resIni = '';
        
        $year = date("Y").'-';
        $expr = '/(?<=\s|^)[a-z]/i';
        $input = $request->except(['_token', 'document', 'funding_amount']);

        if($request->has('classification')){
            $classificationName = DropdownOption::where('id', $input['classification'])->pluck('name')->first();
            preg_match_all($expr, $classificationName, $matches);
            $result = implode('', $matches[0]);
            $classIni = strtoupper($result).'-';
        }
        if($request->has('category')){
            $categoryName = DropdownOption::where('id', $input['category'])->pluck('name')->first();
            preg_match_all($expr, $categoryName, $matches);
            $result = implode('', $matches[0]);
            $catIni = strtoupper($result).'-';
        }
        if($request->has('researchers')){
            $researchers = $input['researchers'];
            $name = preg_split("/\//", $researchers);
            preg_match_all($expr, $name[0], $matches);
            $result = implode('', $matches[0]);
            $resIni = strtoupper($result).'-';
        }

        $researchCodeIni = $classIni.$catIni.$resIni.$year;
        $lastID = Research::withTrashed()->where('research_code', 'like', '%'.$researchCodeIni.'%')
            ->pluck('research_code')->last();
        
        if($lastID == null){
            $researchCode = $classIni.$catIni.$resIni.$year.'01';
        }
        else{
            $lastIdSplit = preg_split('/-/',$lastID);
            if($lastIdSplit[2].'-' === $resIni){
                
                $lastIdDigit = (int) end($lastIdSplit);
                $lastIdDigit++;
                if($lastIdDigit < 10){
                    $lastIdDigit = '0'.$lastIdDigit;
                }
                $researchCode = $classIni.$catIni.$resIni.$year.$lastIdDigit;
            }
            else{
                $researchCode = $classIni.$catIni.$resIni.$year.'01';
            }
        }

        $funding_amount = $request->funding_amount;    

        $funding_amount = str_replace( ',' , '', $funding_amount);

        Research::create([
            'research_code' => $researchCode, 
            'user_id' => auth()->id(), 
            'funding_amount' => $funding_amount,
            'nature_of_involvement' => 11
        ]);

        Research::where('research_code', $researchCode)->update($input);

        if($request->has('document')){
            
            $documents = $request->input('document');
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RR-'.$researchCode.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $researchCode,
                        'research_form_id' => 1,
                        'filename' => $fileName,
                        
                    ]);
                }
            }
        }

        return redirect()->route('research.index')->with('success', 'Research Registered Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Research $research)
    {
        $this->authorize('view', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('1')");

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();
        $research= Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();

        //$values = Research::where('research_code', $research->research_code)->first()->toArray();

        $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$research->department_id.")");

        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['status']);
        $value = $value->toArray();

        $colleges = College::all();

        return view('research.show', compact('research', 'researchFields', 'value', 'researchDocuments', 'colleges', 'collegeOfDepartment'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research)
    {
        $this->authorize('update', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id(1)");

        $values = Research::where('research_code', $research->research_code)->where('user_id', auth()->id())
                ->first()->toArray();
    
        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();
        $colleges = College::all();

        $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$research->department_id.")");

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research->status)->first();
        if ($research->nature_of_involvement == 11)
            return view('research.edit', compact('research', 'researchFields', 'values', 'researchDocuments', 'colleges', 'researchStatus', 'collegeOfDepartment'));
        else
            return view('research.edit-non-lead', compact('research', 'researchFields', 'values', 'researchDocuments', 'colleges', 'researchStatus', 'collegeOfDepartment'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Research $research)
    {
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'classification' => 'required',
            'status' => 'required',
            'category' => 'required',
            'agenda' => 'required',
            'title' => 'required',
            // 'researcher' => 'required',
            'keywords' => 'required',
            // 'nature_of_involvement' => 'required',
            'research_type' => 'required',
            'funding_type' => 'required',
            'currency_funding_amount' => 'required',
            'funding_amount' => 'numeric',
            'funding_agency' => 'required_if:funding_type,23',
            // 'start_date' => 'required_unless:status,32|date',
            // 'target_date' => 'required_unless: status, 28|after_or_equal: start_date|date',
            // 'completion_date' => 'date|after_or_equal:start_date|required_if:status, 28',
            'college_id' => 'required',
            'department_id' => 'required',
            // 'description' => 'required',
        ]);

        $input = $request->except(['_token', '_method', 'document', 'funding_type']);
        $funding_amount = $request->funding_amount;    
        $funding_amount = str_replace( ',' , '', $funding_amount);
        $research->update($input);
        $research->update([
            'funding_amount' => $funding_amount
        ]);
        
        if($request->has('document')){
            $documents = $request->input('document');
            $count = 1;
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RR-'.$research->research_code.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    ResearchDocument::create([
                        'research_code' => $research->research_code,
                        'research_form_id' => 1,
                        'filename' => $fileName,
                        
                    ]);
                }
            }
        }

        return redirect()->route('research.show', $research->id)->with('success', 'Research Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research)
    {
        $this->authorize('delete', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
        return view('inactive');

        $research->delete();

        return redirect()->route('research.index')->with('success', 'Research Deleted Successfully');
    }

    public function complete($complete){
        if(ResearchForm::where('id', 2)->pluck('is_active')->first() == 0)
            return view('inactive');
        $research = Research::where('id', $complete)->pluck('research_code')->first();
        $researchCompleteId = ResearchComplete::where('research_code', $research)->pluck('id')->first();
        if($researchCompleteId != null)
            return redirect()->route('research.completed.edit', ['research' => $complete, 'completed' =>  $researchCompleteId]);
        else
            return redirect()->route('research.completed.create', $complete);
    }

    public function publication($publication){
        if(ResearchForm::where('id', 3)->pluck('is_active')->first() == 0)
            return view('inactive');
        $research = Research::where('id', $publication)->pluck('research_code')->first();
        $researchPublicationId = ResearchPublication::where('research_code', $research)->pluck('id')->first();
        if($researchPublicationId != null)
            return redirect()->route('research.publication.edit', ['research' => $publication, 'publication' =>  $researchPublicationId]);
        else
            return redirect()->route('research.publication.create', $publication);
    }

    public function presentation($presentation){
        if(ResearchForm::where('id', 4)->pluck('is_active')->first() == 0)
            return view('inactive');
        $research = Research::where('id', $presentation)->pluck('research_code')->first();
        $researchPresentationId = ResearchPresentation::where('research_code', $research)->pluck('id')->first();
        if($researchPresentationId != null)
            return redirect()->route('research.presentation.edit', ['research' => $presentation, 'presentation' =>  $researchPresentationId]);
        else
            return redirect()->route('research.presentation.create', $presentation);
    }

    public function copyright($copyright){
        if(ResearchForm::where('id', 7)->pluck('is_active')->first() == 0)
            return view('inactive');
        $research = Research::where('id', $copyright)->pluck('research_code')->first();
        $researchCopyrightId = ResearchCopyright::where('research_code', $research)->pluck('id')->first();
        if($researchCopyrightId != null)
            return redirect()->route('research.copyrighted.edit', ['research' => $copyright, 'copyrighted' =>  $researchCopyrightId]);
        else
            return redirect()->route('research.copyrighted.create', $copyright);
    }

    public function removeDoc($filename){
        ResearchDocument::where('filename', $filename)->delete();
        return true;
    }

    public function useResearchCode(Request $request){
        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        
        if (Research::where('research_code', $request->code)->exists())
            return redirect()->route('research.code.create', $request->code);
        else
            return redirect()->route('research.index')->with('code-missing', 'Code does not exist');
    }

    public function addResearch($research_code){

        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id('1')");

        $research = Research::where('research_code', $research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
            ->join('currencies', 'currencies.id', 'research.currency')
            ->select('research.*', 'dropdown_options.name as status_name', 'currencies.code as currency_code')->first()->toArray();
        $research = collect($research);
        $research = $research->except(['nature_of_involvement']);
        $values = $research;
        $research = json_decode(json_encode($research), FALSE);
        // dd($values);
        // $research = collect($research);
        $researchers = Research::where('research_code', $research_code)->pluck('researchers')->all();

        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();

        $departments = Department::all();
        $colleges = College::all();
        return view('research.code-create', compact('research', 'researchers', 'researchDocuments', 'values', 'researchFields', 'departments', 'colleges'));
    }

    public function saveResearch($research_code, Request $request){

        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $research = Research::where('research_code', $research_code)->first()->toArray();
        $research = collect($research);
        $researchFiltered= $research->except(['id', 'college_id', 'department_id', 'researchers', 'nature_of_involvement', 'user_id', 'created_at', 'updated_at', 'deleted_at']);
        $fromRequest = $request->except(['_token', 'document']);
        $data = array_merge($researchFiltered->toArray(), $fromRequest);
        $data = Arr::add($data, 'user_id', auth()->id());
        // dd($data);
        Research::create($data); 
        Research::where('research_code', $research_code)->update([
            'researchers' => $research['researchers'].', '.auth()->user()->first_name.' '.auth()->user()->last_name
        ]);
        return redirect()->route('research.index')->with('success', 'Research Added Successfully');
    }

    public function retrieve($research_code){
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        $researchLead = Research::where('research_code', $research_code)->first()->toArray();
        $researchLead = collect($researchLead);
        $researchLead = $researchLead->except(['id','research_code', 'college_id', 'department_id', 'nature_of_involvement', 'user_id', 'created_at', 'updated_at', 'deleted_at' ]);
        Research::where('research_code', $research_code)->where('user_id', auth()->id())
                ->update($researchLead->toArray());
        $research = Research::where('research_code', $research_code)->where('user_id', auth()->id())->first();
        return redirect()->route('research.show', $research->id)->with('success', 'Latest Version Retrieved Successfully');
    }

    public function addDocument($research_code, $report_category_id){
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        return view('research.add-documents', compact('research_code', 'report_category_id'));
    }
    public function saveDocument($research_code, $report_category_id, Request $request){
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');
        if($report_category_id == 5){
            $citation_id = $research_code;
            $research_code = ResearchCitation::where('id', $citation_id)->pluck('research_code')->first();
        }
        if($report_category_id == 6){
            $utilization_id = $research_code;
            $research_code = ResearchUtilization::where('id', $utilization_id)->pluck('research_code')->first();
        }
        if($request->has('document')){
            $documents = $request->input('document');
            $count = 1;
            foreach($documents as $document){
                $temporaryFile = TemporaryFile::where('folder', $document)->first();
                if($temporaryFile){
                    $temporaryPath = "documents/tmp/".$document."/".$temporaryFile->filename;
                    $info = pathinfo(storage_path().'/documents/tmp/'.$document."/".$temporaryFile->filename);
                    $ext = $info['extension'];
                    $fileName = 'RR-'.$research_code.'-'.now()->timestamp.uniqid().'.'.$ext;
                    $newPath = "documents/".$fileName;
                    Storage::move($temporaryPath, $newPath);
                    Storage::deleteDirectory("documents/tmp/".$document);
                    $temporaryFile->delete();

                    if($report_category_id == 5){//citations
                        ResearchDocument::create([
                            'research_code' => $research_code,
                            'research_form_id' => $report_category_id,
                            'research_citation_id' => $citation_id,
                            'filename' => $fileName,
                        ]);
                    }
                    elseif($report_category_id == 6){
                        ResearchDocument::create([
                            'research_code' => $research_code,
                            'research_form_id' => $report_category_id,
                            'research_utilization_id' => $utilization_id,
                            'filename' => $fileName,
                        ]);
                    }
                    else{
                        ResearchDocument::create([
                            'research_code' => $research_code,
                            'research_form_id' => $report_category_id,
                            'filename' => $fileName,
                            
                        ]);
                    }
                }
            }
        }
        return redirect()->route('faculty.index')->with('success', 'Document added successfully');
    }
}
