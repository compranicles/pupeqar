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
use App\Rules\Keyword;


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
        $researches = Research::where('user_id', auth()->id())->where('is_active_member', 1)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->join('colleges', 'colleges.id', 'research.college_id')
                ->select('research.*', 'dropdown_options.name as status_name', 'colleges.name as college_name')
                ->orderBy('research.updated_at', 'desc')
                ->get();

        $research_in_colleges = Research::join('colleges', 'research.college_id', 'colleges.id')
                                        ->select('colleges.name')
                                        ->distinct()
                                        ->get();

        return view('research.index', compact('researches', 'researchStatus', 'research_in_colleges'));
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

        $value = $request->input('funding_amount');
        $value = (float) str_replace(",", "", $value);
        $value = number_format($value,2,'.','');

        // dd($request->input('start_date'));
        $request->merge([
            'funding_amount' => $value,
        ]);

        // $request->validate([]);

        $request->validate([
            'funding_amount' => 'numeric',
            'funding_agency' => 'required_if:funding_type,23',
            'keywords' => new Keyword,
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

        $research_id = Research::insertGetId([
            'research_code' => $researchCode, 
            'user_id' => auth()->id(), 
            'funding_amount' => $funding_amount,
            'nature_of_involvement' => 11
        ]);

        Research::where('id', $research_id)->update($input);

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

        return redirect()->route('research.index')->with('success', 'Research has been registered.');
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

        if ($research->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$research->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

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

        if($research->nature_of_involvement != 11){
            $values = Research::where('research_code', $research->research_code)->where('user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'research.status')
                        ->join('currencies', 'currencies.id', 'research.currency_funding_amount')
                        ->select('research.*', 'dropdown_options.name as status_name', 'currencies.code as currency_funding_amount_code')
                        ->first()->toArray();
        }
        else{
            $values = Research::where('research_code', $research->research_code)->where('user_id', auth()->id())->first()->toArray(); 
        }
    
        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();
        $colleges = College::all();

        if ($research->department_id != null) {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(".$research->department_id.")");
        }
        else {
            $collegeOfDepartment = DB::select("CALL get_college_and_department_by_department_id(0)");
        }

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research->status)->first();
        if ($research->nature_of_involvement == 11)
            return view('research.edit', compact('research', 'researchFields', 'values', 'researchDocuments', 'colleges', 'researchStatus', 'collegeOfDepartment'));

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

            $value = $request->input('funding_amount');
            $value = (float) str_replace(",", "", $value);
            $value = number_format($value,2,'.','');

            $request->merge([
                'funding_amount' => $value,
            ]);

            $request->validate([
                'funding_amount' => 'numeric',
                'funding_agency' => 'required_if:funding_type,23',
                'keywords' => new Keyword,
            ]);
    

        $input = $request->except(['_token', '_method', 'document', 'funding_type']);
        $inputOtherResearchers = $request->except(['_token', '_method', 'document', 'funding_type', 'college_id', 'department_id', 'nature_of_involvement']);
        $funding_amount = $request->funding_amount;    
        $funding_amount = str_replace( ',' , '', $funding_amount);

        $research->update($input);
        Research::where('research_code', $research->research_code)->update($inputOtherResearchers);
        Research::where('research_code', $research->research_code)->update([
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

        return redirect()->route('research.show', $research->id)->with('success', 'Research has been updated.');
    }

    public function updateNonLead (Request $request, Research $research)
    {
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $request->validate([
            'nature_of_involvement' => 'required',
            'college_id' => 'required',
            'department_id' => 'required',
        ]);
        // dd($request);
        $input = $request->except(['_token', '_method', 'document']);
        Research::where('id', $research->id)->update($input);

        return redirect()->route('research.show', $research)->with('success', 'Research has been updated.');
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
        ResearchDocument::where('research_id', $research->id)->delete();

        return redirect()->route('research.index')->with('success', 'Research has been deleted.');
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
        
        if(Research::where('research_code', $request->input('code'))->where('user_id', auth()->id())->exists()){
            return redirect()->route('research.index')->with('code-missing', 'Research Already added. If it is not displayed, you are already removed by the Lead Researcher/ Team Leader');
        }
        if (Research::where('research_code', $request->code)->exists())
            return redirect()->route('research.code.create', $request->code);
        else
            return redirect()->route('research.index')->with('code-missing', 'Code does not exist');
    }

    public function addResearch($research_code){

        $this->authorize('create', Research::class);
        if(ResearchForm::where('id', 1)->pluck('is_active')->first() == 0)
            return view('inactive');

        $researchFields = DB::select("CALL get_research_fields_by_form_id(1)");

        $research = Research::where('research_code', $research_code)->where('nature_of_involvement', 11)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->join('currencies', 'currencies.id', 'research.currency_funding_amount')
                ->select('research.*', 'dropdown_options.name as status_name', 'currencies.code as currency_funding_amount')
                ->first()->toArray();

        $research = collect($research);
        $research = $research->except(['nature_of_involvement', 'college_id', 'department_id']);
        $values = $research->toArray();
        $research = json_decode(json_encode($research), FALSE);
        // dd($values);
        // $research = collect($research);
        $researchers = Research::where('research_code', $research_code)->pluck('researchers')->all();

        $researchDocuments = ResearchDocument::where('research_code', $research_code)->where('research_form_id', 1)->get()->toArray();

        $departments = Department::all();
        $colleges = College::all();

        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research->status)->first();

        return view('research.code-create', compact('research', 'researchers', 'researchDocuments', 'values', 'researchFields', 'departments', 'colleges', 'researchStatus'));
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
        return redirect()->route('research.index')->with('success', 'Research has been saved');
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
        return redirect()->route('research.show', $research->id)->with('success', 'Latest version has been retrieved.');
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

    public function manageResearchers($research_code){
        $research = Research::where('research_code', $research_code)->where('user_id', auth()->id())->first();
        if($research->nature_of_involvement != 11)
            abort('404');
        $researchers = Research::select('research.id', 'research.user_id',  'research.nature_of_involvement', 'dropdown_options.name as nature_of_involvement_name', 'users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->join('dropdown_options', 'dropdown_options.id', 'research.nature_of_involvement')
                ->where('research.research_code', $research_code)->where('is_active_member', 1)
                ->get();
        $inactive_researchers = Research::select('research.id', 'research.user_id',  'research.nature_of_involvement', 'dropdown_options.name as nature_of_involvement_name', 'users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->join('dropdown_options', 'dropdown_options.id', 'research.nature_of_involvement')
                ->where('research.research_code', $research_code)->where('is_active_member', 0)
                ->get();
        // dd($researchers);
        $nature_of_involvement_dropdown = DropdownOption::where('dropdown_id', 4)->where('is_active', 1)->orderBy('order')->get();
        return view('research.manage-researchers.index', compact('research', 'research_code', 'researchers', 'inactive_researchers','nature_of_involvement_dropdown'));
    }

    public function saveResearchRole($research_code, Request $request){
        Research::where('research_code', $research_code)->where('user_id', $request->input('user_id'))->update([
            'nature_of_involvement' => $request->input('nature_of_involvement')
        ]);
        return redirect()->route('research.manage-researchers', $research_code)->with('success', 'Researcher records has been updated.');
    }

    public function removeResearcher($research_code, Request $request){
        Research::where('research_code', $research_code)->where('user_id', $request->input('user_id'))->update([
            'is_active_member' => 0
        ]);
        $researchers = Research::select('users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->where('research.research_code', $research_code)->where('is_active_member', 1)
                ->get();

        $researcherNewName = '';
        foreach($researchers as $researcher){
            if(count($researchers) == 1)
                $researcherNewName = $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name;
            else
                $researcherNewName .= $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name.', ';
        }
        
        Research::where('research_code', $research_code)->update([
            'researchers' => $researcherNewName
        ]);

        return redirect()->route('research.manage-researchers', $research_code)->with('success', 'Researcher has been removed.');
    }

    public function returnResearcher($research_code, Request $request){
        Research::where('research_code', $research_code)->where('user_id', $request->input('user_id'))->update([
            'is_active_member' => 1
        ]);
        $researchers = Research::select('users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->where('research.research_code', $research_code)->where('is_active_member', 1)
                ->get();

        $researcherNewName = '';
        foreach($researchers as $researcher){
            if(count($researchers) == 1)
                $researcherNewName = $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name;
            else
                $researcherNewName .= $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name.', ';
        }
        
        Research::where('research_code', $research_code)->update([
            'researchers' => $researcherNewName
        ]);

        return redirect()->route('research.manage-researchers', $research_code)->with('success', 'Researcher has been added.');
    }

    public function removeSelf($research_code){
        Research::where('research_code', $research_code)->where('user_id', auth()->id())->update([
            'is_active_member' => 0
        ]);
        $researchers = Research::select('users.first_name', 'users.last_name', 'users.middle_name')
                ->join('users',  'research.user_id', 'users.id')
                ->where('research.research_code', $research_code)->where('is_active_member', 1)
                ->get();

        $researcherNewName = '';
        foreach($researchers as $researcher){
            if(count($researchers) == 1)
                $researcherNewName = $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name;
            else
                $researcherNewName .= $researcher->first_name.' '.(($researcher->middle_name == null) ? '' : $researcher->middle_name.' ').$researcher->last_name.', ';
        }
        
        Research::where('research_code', $research_code)->update([
            'researchers' => $researcherNewName
        ]);

        return redirect()->route('research.index')->with('success', 'Research has been removed.');
    }
}

