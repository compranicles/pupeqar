<?php

namespace App\Http\Controllers\Research;

use App\Models\Research;
use Illuminate\Http\Request;
use App\Models\TemporaryFile;
use App\Models\ResearchComplete;
use App\Models\ResearchDocument;
use App\Models\ResearchCopyright;
use App\Models\Maintenance\College;
use App\Models\ResearchPublication;
use App\Http\Controllers\Controller;
use App\Models\ResearchPresentation;
use App\Models\Maintenance\Department;
use Illuminate\Support\Facades\Storage;
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
        $researches = Research::where('user_id', auth()->id())->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->get();
        return view('research.index', compact('researches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 1)->where('is_active', 1)
            ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
            ->select('research_fields.*', 'field_types.name as field_type_name')
            ->orderBy('order')->get();
        // dd($researchfields);

        $departments = Department::all();
        $colleges = College::all();
        return view('research.create', compact('researchFields', 'departments', 'colleges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $departmentIni = '';
        $classIni = '';
        $catIni = '';
        $resIni = '';
        
        $year = date("Y").'-';
        $expr = '/(?<=\s|^)[a-z]/i';
        $input = $request->except(['_token', 'document', 'college_id']);

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
        // $lastID = Research::where('classification', $input['classification'] ?? null)->where('category', $input['category'] ?? null)
        //     ->whereYear('created_at', '=', date("Y"))
        //     ->pluck('research_code')->last();
        $researchCodeIni = $classIni.$catIni.$resIni.$year;
        $lastID = Research::where('research_code', 'like', '%'.$researchCodeIni.'%')
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
        Research::create(
            ['research_code' => $researchCode, 'user_id' => auth()->id()]
        );
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
        $researchFields = ResearchField::where('research_fields.research_form_id', 1)
                ->join('field_types', 'field_types.id', 'research_fields.field_type_id')->where('is_active', 1)
                ->select('research_fields.*', 'field_types.name as field_type_name')
                ->orderBy('order')->get();
        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();
        $research= Research::where('research_code', $research->research_code)->join('dropdown_options', 'dropdown_options.id', 'research.status')
                ->select('research.*', 'dropdown_options.name as status_name')->first();

        //$values = Research::where('research_code', $research->research_code)->first()->toArray();

        $value = $research;
        $value->toArray();
        $value = collect($research);
        $value = $value->except(['status']);
        $value = $value->toArray();
        $collegeAndDepartment = $research->join('departments', 'departments.id', 'research.department_id')
                                ->join('colleges', 'colleges.id', 'departments.college_id')
                                ->select('colleges.name AS college_name', 'departments.name AS department_name')
                                ->first();
        $researchStatus = DropdownOption::where('dropdown_options.dropdown_id', 7)->where('id', $research->status)->first();
        return view('research.show', compact('research', 'researchFields', 'value', 'researchDocuments', 'collegeAndDepartment', 'value', 'researchStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Research $research)
    {
        $researchFields = ResearchField::where('research_fields.research_form_id', 1)->where('is_active', 1)
                ->join('field_types', 'field_types.id', 'research_fields.field_type_id')
                ->select('research_fields.*', 'field_types.name as field_type_name')
                ->orderBy('order')->get();
        $values = Research::where('research_code', $research->research_code)->first()->toArray();
        $researchDocuments = ResearchDocument::where('research_code', $research->research_code)->where('research_form_id', 1)->get()->toArray();
        $colleges = College::all();
        $departments = Department::all();
        $collegeAndDepartment = $research->join('departments', 'departments.id', 'research.department_id')
                                ->join('colleges', 'colleges.id', 'departments.college_id')
                                ->select('colleges.id AS college_id', 'colleges.name AS college_name', 'departments.id AS department_id', 'departments.name AS department_name')
                                ->first();
        return view('research.edit', compact('research', 'researchFields', 'values', 'researchDocuments', 'colleges', 'departments', 'collegeAndDepartment'));
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
        $input = $request->except(['_token', '_method', 'description', 'document']);

        $research->update($input);
        
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

        return redirect()->route('research.show', $research->research_code)->with('success', 'Research Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Research $research)
    {
        $research->delete();

        return redirect()->route('research.index')->with('success', 'Research Deleted Successfully');
    }

    public function complete($complete){
        $researchCompleteId = ResearchComplete::where('research_code', $complete)->pluck('id')->first();
        if($researchCompleteId != null)
            return redirect()->route('research.completed.edit', ['research' => $complete, 'completed' =>  $researchCompleteId]);
        else
            return redirect()->route('research.completed.create', $complete);
    }

    public function publication($publication){
        $researchPublicationId = ResearchPublication::where('research_code', $publication)->pluck('id')->first();
        if($researchPublicationId != null)
            return redirect()->route('research.publication.edit', ['research' => $publication, 'publication' =>  $researchPublicationId]);
        else
            return redirect()->route('research.publication.create', $publication);
    }
    public function presentation($presentation){
        $researchPresentationId = ResearchPresentation::where('research_code', $presentation)->pluck('id')->first();
        if($researchPresentationId != null)
            return redirect()->route('research.presentation.edit', ['research' => $presentation, 'presentation' =>  $researchPresentationId]);
        else
            return redirect()->route('research.presentation.create', $presentation);
    }
    public function copyright($copyright){
        $researchCopyrightId = ResearchCopyright::where('research_code', $copyright)->pluck('id')->first();
        if($researchCopyrightId != null)
            return redirect()->route('research.copyrighted.edit', ['research' => $copyright, 'copyrighted' =>  $researchCopyrightId]);
        else
            return redirect()->route('research.copyrighted.create', $copyright);
    }
    public function removeDoc($filename){
        ResearchDocument::where('filename', $filename)->delete();
        Storage::delete('documents/'.$filename);
        return true;
    }
}
