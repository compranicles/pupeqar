<?php

namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\User;
use App\Models\Report;
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use App\Models\Maintenance\GenerateTable;
use App\Models\Maintenance\GenerateColumn;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;

class IndividualAccomplishmentReportExport implements FromView, WithEvents
{
    function __construct($source_type, $reportFormat, $source_generate, $year_generate, $quarter_generate, $id, $table_columns, $table_contents, $table_format) {
        $this->source_type = $source_type;
        $this->report_format = $reportFormat;
        $this->source_generate = $source_generate;
        $this->year_generate = $year_generate;
        $this->quarter_generate = $quarter_generate;
        $this->id = $id;
        $this->table_columns = $table_columns;
        $this->table_contents = $table_contents;
        $this->table_format = $table_format;

        $user = User::where('id', auth()->id())->first();
        $this->name_user = $user->last_name.', '.$user->first_name.' '.$user->middle_name; 
    }

    public function view(): View
    {
        $source_type = $this->source_type;
        $reportFormat = $this->report_format;
        $source_generate = $this->source_generate;
        $year_generate = $this->year_generate;
        $quarter_generate = $this->quarter_generate;
        $id = $this->id;

        $source_type = '';
        if($reportFormat == "academic"){
            if($source_generate == "department"){
                $source_type = "department";
                $department_id = $id;
                $data = Department::where('id', $department_id)->first();
                $table_format = GenerateTable::where('type_id', 2)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.department_id', $department_id)
                            ->where('reports.chairperson_approval', 1)
                            ->whereYear('reports.updated_at', $year_generate)
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $quarter_generate)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
            elseif($source_generate == "college"){
                $source_type = "college";
                $college_id = $id;
                $data = College::where('id', $college_id)->first();
                $table_format = GenerateTable::where('type_id', 2)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.college_id', $college_id)
                            ->where('reports.dean_approval', 1)
                            ->whereYear('reports.updated_at', $year_generate)
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $quarter_generate)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
            elseif($source_generate == "my"){
                $source_type = "individual";
                $user_id = $id;
                $data = User::where('id', $user_id)->select('users.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as name"))->first();
                $table_format = GenerateTable::where('type_id', 2)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->whereYear('reports.updated_at', $year_generate)
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $quarter_generate)
                            ->where('reports.user_id', $user_id)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->join('departments', 'departments.id', 'reports.department_id')
                            ->join('colleges', 'colleges.id', 'reports.college_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
        }
        elseif($source_type == "admin"){
            if($source_generate == "department"){
                $source_type = "department";
                $department_id = $id;
                $data = Department::where('id', $department_id)->first();
                $table_format = GenerateTable::where('type_id', 1)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.department_id', $department_id)
                            ->where('reports.chairperson_approval', 1)
                            ->whereYear('reports.updated_at', $year_generate)
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $quarter_generate)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
            elseif($source_generate == "college"){
                $source_type = "college";
                $college_id = $id;
                $data = College::where('id', $college_id)->first();
                $table_format = GenerateTable::where('type_id', 1)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.college_id', $college_id)
                            ->where('reports.dean_approval', 1)
                            ->whereYear('reports.updated_at', $year_generate)
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $quarter_generate)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
            elseif($source_generate == "my"){
                $source_type = "individual";
                $user_id = $id;
                $data = User::where('id', $user_id)->select('users.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as name"))->first();
                $table_format = GenerateTable::where('type_id', 1)->get();
                $table_columns = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0")
                        $table_columns[$format->id] = [];
                    else
                        $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }
                
                $table_contents = [];
                foreach ($table_format as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $table_contents[$format->id] = [];
                    else
                        $table_contents[$format->id] = Report::where('reports.report_category_id', $format->report_category_id)
                            ->whereYear('reports.updated_at', $year_generate)
                            ->where(DB::raw('QUARTER(reports.updated_at)'), $quarter_generate)
                            ->where('reports.user_id', $user_id)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
        }
        return view('reports.generate.example', compact('table_format', 'table_columns', 'table_contents', 'source_type', 'data', 'reportFormat', 'source_generate', 'year_generate', 'quarter_generate', 'id'));
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(Aftersheet $event) {
                $event->sheet->getSheetView()->setZoomScale(60);
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Arial');
                // $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize(12);
                $event->sheet->getDefaultColumnDimension()->setWidth(30.57);
                // $event->sheet->getStyle('A1:Z500')->getAlignment()->setWrapText(true);
                if ($this->source_type == "individual")
                    if ($this->report_format == "academic")
                    {   
                        $event->sheet->setCellValue('A1', 'FACULTY INDIVIDUAL ACCOMPLISHMENT REPORT');
                        $event->sheet->getStyle('A1')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 20,
                            ]
                        ]);
                    }
                    else {
                        $event->sheet->setCellValue('A1', 'ADMIN INDIVIDUAL ACCOMPLISHMENT REPORT');
                        $event->sheet->getStyle('A1')->applyFromArray([
                            'font' => [
                                'bold' => true,
                                'size' => 20,
                            ]
                        ]);
                    }
                else {
                    $event->sheet->setCellValue('A1', 'CONSOLIDATED ACCOMPLISHMENT REPORT');
                    $event->sheet->getStyle('A1')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 20,
                        ]
                    ]);
                }

                $event->sheet->getStyle('A1:Z500')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                // $event->sheet->getRowDimension('1')->setRowHeight(26.25);
                $event->sheet->mergeCells('A1:H1');
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->setCellValue('C2', 'NAME:');
                $event->sheet->getStyle('C2')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('C2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('D2:F2');
                $event->sheet->setCellValue('D2', $this->name_user);
                $event->sheet->getStyle('D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('D2:F2')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('D2')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);

                $event->sheet->setCellValue('C3', 'QUARTER:');
                $event->sheet->getStyle('C3')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->setCellValue('D3', $this->quarter_generate);
                $event->sheet->getStyle('D3')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('D3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('D3')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $event->sheet->setCellValue('E3', 'CALENDAR YEAR:');
                $event->sheet->getStyle('E3')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('E3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->setCellValue('F3', $this->year_generate);
                $event->sheet->getStyle('F3')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('F3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('F3')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $event->sheet->mergeCells('A7:K7');
                $event->sheet->getStyle('A7')->applyFromArray([
                    'font' => [
                        'italic' => true,
                        'size' => 14,
                    ]
                ]);
                $event->sheet->getStyle('A7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->mergeCells('A8:K8');
                $event->sheet->getStyle('A8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FF800000");
                $event->sheet->getStyle('A8')->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                $event->sheet->getStyle('A8')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('A8')->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                    ]
                ]);

                $count = 9;
                $table_format = (array) $this->table_format;
                $table_columns = (array) $this->table_columns;
                // dd($table_columns[42]);
                foreach($table_format as $format) {
                    if ($format->is_table == 1) {
                        $length = count($table_columns[$format->id]);
                        $letter = chr($length + 64);
                        //for columns
                        if ($length > 1) {
                            // $event->sheet->mergeCells('A'.$count);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FF203764");
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->applyFromArray([
                                'font' => [
                                    'name' => 'Arial',
                                ]
                            ]);
                        }
                        $count++;
                    }
                    
                }
            }
        ];
    }
}
