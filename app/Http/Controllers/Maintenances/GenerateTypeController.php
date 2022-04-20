<?php

namespace App\Http\Controllers\Maintenances;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Maintenance\{
    GenerateColumn,
    GenerateTable,
    GenerateType,
    ReportCategory,
};

class GenerateTypeController extends Controller
{
    public function index(){
        $this->authorize('manage', GenerateType::class);

        $report_types = GenerateType::all();
        return view('maintenances.generate.index', compact('report_types'));
    }

    public function view($type_id){
        $this->authorize('manage', GenerateType::class);

        $type = GenerateType::find($type_id);
        $tables = GenerateTable::where('type_id', $type_id)->get();
        return view('maintenances.generate.show', compact('type', 'tables'));
    }

    public function edit($type_id, $table_id){
        $this->authorize('manage', GenerateType::class);

        $table = GenerateTable::find($table_id);
        $report_categories = ReportCategory::all();
        $footers = json_decode($table->footers, true);
        $columns  = GenerateColumn::where('table_id', $table_id)->orderBy('order')->get();
        return view('maintenances.generate.edit', compact('table', 'report_categories', 'footers', 'columns'));
    }

    public function save($type_id, $table_id, Request $request){
        $this->authorize('manage', GenerateType::class);

        $table = GenerateTable::find($table_id);
        $footers = $request->input('footers');
        $tempArray = explode(";", $footers);
        $footers = json_encode($tempArray, JSON_FORCE_OBJECT);

        $table->update([
            'name' => $request->input('name') ?? null,
            'report_category_id' => $request->input('report_category') ?? null,
            'footers' => $footers ?? null
        ]);

        return redirect()->route('maintenance.generate.edit', ['type_id' => $type_id, 'table_id'=> $table_id])->with('success', $table->name.' updated successfully.');
    }

    public function editColumn($type_id, $table_id, $column_id){
        $column_info = GenerateColumn::find($column_id);
        return view('maintenances.generate.edit-column', compact('type_id', 'table_id', 'column_id', 'column_info'));
    }

    public function saveColumn($type_id, $table_id, $column_id, Request $request){
        GenerateColumn::where('id', $column_id)->update([
            'name' => $request->input('name')
        ]);
        return redirect()->route('maintenance.generate.edit', ['type_id' => $type_id, 'table_id'=> $table_id])->with('success', 'Renamed successfully');
    }


    public function activate($column_id){
        GenerateColumn::where('id', $column_id)->update([
            'is_active' => 1
        ]);

        return true;
    }

    public function inactivate($column_id){
        GenerateColumn::where('id', $column_id)->update([
            'is_active' => 0
        ]);

        return true;
    }

    public function arrange(Request $request){

        $options = json_decode($request->data, true);
        
        for($i = 0; $i < count($options); $i++){
            GenerateColumn::where('id', $options[$i])->update([
                'order' => $i+1
            ]);
        }

        return true;
    }
}
