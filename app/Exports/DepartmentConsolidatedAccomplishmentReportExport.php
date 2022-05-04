<?php

namespace App\Exports;

use App\Models\{
    User,
    Report,
    FacultyResearcher,
    FacultyExtensionist,
};
use Illuminate\Support\Facades\DB;
use App\Models\Maintenance\College;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Maintenance\Department;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Maintenance\GenerateTable;
use App\Models\Maintenance\GenerateColumn;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\Services\NameConcatenationService;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class DepartmentConsolidatedAccomplishmentReportExport implements FromView, WithEvents
{
    function __construct($source_type, $reportFormat, $source_generate, $year_generate, $quarter_generate,
         $department, $id, $get_department, $faculty_researcher, $faculty_extensionist) {
        $this->source_type = $source_type;
        $this->report_format = $reportFormat;
        $this->source_generate = $source_generate;
        $this->year_generate = $year_generate;
        $this->quarter_generate = $quarter_generate;
        $this->department = $department;
        $this->id = $id;

        $user = User::where('id', auth()->id())->first();
        $this->signature = $user->signature;
        // Tells if the user is an FE, FR, or both not.
        $this->isFacultyExt = FacultyExtensionist::where('user_id', $user->id)->first();
        $this->isFacultyRes = FacultyResearcher::where('user_id', $user->id)->first();

        $this->arranged_name = (new NameConcatenationService())->getConcatenatedNameByUserAndRoleName($user, " ");
        if ($faculty_researcher != null) {
            $this->fr_signature = User::where('id', $faculty_researcher['user_id'])->pluck('users.signature')->first();
            $this->fr_name = (new NameConcatenationService())->getConcatenatedNameByUserAndRoleName($faculty_researcher, "Researcher");
        } else {
            $this->fr_name = '';
            $this->fr_signature = '';
        }

        if ($faculty_extensionist != null) {
            $this->fe_signature = User::where('id', $faculty_extensionist['user_id'])->pluck('users.signature')->first();
            $this->fe_name = (new NameConcatenationService())->getConcatenatedNameByUserAndRoleName($faculty_extensionist, "Extensionist");
        } else {
            $this->fe_name = '';
            $this->fe_signature = '';
        }
        
        $this->departmentName = $get_department;
    }

    public function view(): View
    {
        $source_type = $this->source_type;
        $reportFormat = $this->report_format;
        $source_generate = $this->source_generate;
        $year_generate = $this->year_generate;
        $quarter_generate = $this->quarter_generate;
        $id = $this->id;
        $table_format;
        $table_columns;
        $table_contents;
        $data;
        $source_type;
        
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
                            ->where('reports.report_year', $year_generate)
                            ->where('reports.report_quarter', $quarter_generate)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }

            }
        }
        elseif($reportFormat == "admin"){
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
                            ->where('reports.report_year', $year_generate)
                            ->where('reports.report_quarter', $quarter_generate)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
            
        }

        $this->table_format = $table_format;
        $this->table_columns = $table_columns;
        $this->table_contents = $table_contents;
        return view('reports.generate.example', compact('table_format', 'table_columns', 'table_contents', 'source_type', 'data', 'reportFormat', 'source_generate', 'year_generate', 'quarter_generate', 'id'));
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(Aftersheet $event) {
                $event->sheet->getSheetView()->setZoomScale(70);
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Arial');
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize(12);
                $event->sheet->getDefaultColumnDimension()->setWidth(33);
                $event->sheet->mergeCells('A1:G1');
                $event->sheet->freezePane('C1');
                if ($this->source_type == "department") {
                    $event->sheet->setCellValue('A1', 'CONSOLIDATED DEPARTMENT QUARTERLY ACCOMPLISHMENT REPORT');
                    $event->sheet->getStyle('A1')->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'size' => 20,
                        ]
                    ]);
                }

                $event->sheet->getStyle('A1:Z500')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                if ($this->report_format == "academic") {
                    $event->sheet->setCellValue('B2', 'DEPARTMENT:');
                } elseif ($this->report_format == "admin") {
                    $event->sheet->setCellValue('B2', 'SECTION:');
                }
                $event->sheet->getStyle('B2')->applyFromArray([
                    'font' => [
                        'size' => 16,
                        'bold' => true,
                    ]
                ]);
                $event->sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('C2:F2');
                $event->sheet->setCellValue('C2', $this->departmentName);
                $event->sheet->getStyle('C2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('C2:F2')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('C2')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);

                // Name
                if ($this->isFacultyRes != null) {
                    $event->sheet->setCellValue('B3', 'FACULTY RESEARCHER:');
                } elseif ($this->isFacultyExt != null) {
                    $event->sheet->setCellValue('B3', 'FACULTY EXTENSIONIST:');
                } else {
                    $event->sheet->setCellValue('B3', 'CHAIRPERSON:');
                }
                $event->sheet->getStyle('B3')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('C3:F3');
                $event->sheet->setCellValue('C3', $this->arranged_name);
                $event->sheet->getStyle('C3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('C3:F3')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('C3')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);

                $event->sheet->setCellValue('B4', 'QUARTER:');
                $event->sheet->getStyle('B4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('B4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->setCellValue('C4', $this->quarter_generate);
                $event->sheet->getStyle('C4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('C4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('C4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $event->sheet->setCellValue('D4', 'CALENDAR YEAR:');
                $event->sheet->getStyle('D4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('D4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->setCellValue('E4', $this->year_generate);
                $event->sheet->getStyle('E4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('E4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $count = 7;
                $table_format = $this->table_format;
                $table_columns = $this->table_columns;
                $table_contents = $this->table_contents;
                foreach($table_format as $format) {
                    if($format->is_table == '0'){
                        
                        //title
                        $event->sheet->mergeCells('A'.$count.':K'.$count);
                        $event->sheet->getStyle('A'.$count)->getAlignment()->setWrapText(true);
                        $event->sheet->getStyle('A'.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFC00000");
                        $event->sheet->getStyle('A'.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
                        $event->sheet->getStyle('A'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $event->sheet->getRowDimension($count)->setRowHeight(30);
                        $event->sheet->getStyle('A'.$count)->applyFromArray([
                            'font' => [
                                'name' => 'Arial',
                            ]
                        ]);
                        $count++;

                    }
                    elseif($format->is_table == '1') {
                        $length = count($table_columns[$format->id]);
                        if ($length == null){
                            $length = 2;
                        }
                        else{
                            $length = $length+4;
                        }
                        $letter = Coordinate::stringFromColumnIndex($length);

                        // title
                        $event->sheet->mergeCells('A'.$count.':'.$letter.$count);
                        $event->sheet->getStyle('A'.$count)->getAlignment()->setWrapText(true);
                        if ($format->is_individual == '0') {
                            $event->sheet->getStyle('A'.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FF002060");
                            $event->sheet->getStyle('A'.$count)->getFont()->getColor()->setARGB('ffffffff');
                        }
                        else {
                            $event->sheet->getStyle('A'.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFFFC000");
                            $event->sheet->getStyle('A'.$count)->getFont()->getColor()->setARGB('FFC00000');
                        }
                        
                        $event->sheet->getRowDimension($count)->setRowHeight(30);
                        $event->sheet->getStyle('A'.$count)->applyFromArray([
                            'font' => [
                                'name' => 'Arial',
                            ]
                        ]);
                        $count++;

                        //column
                        $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                        $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $event->sheet->getStyle('A'.$count.':'.$letter.$count)->applyFromArray([
                            'font' => [
                                'name' => 'Arial',
                                'bold' => true, 
                                'size' => 14
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => 'FF515256'],
                                ],
                            ],
                        ]);
                        $count++;

                        //contents
                        foreach($table_contents[$format->id] as $contents){
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->applyFromArray([
                                'font' => [
                                    'name' => 'Arial',
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                ],
                            ]);
                            $count++;
                        }

                        if($table_contents[$format->id] == null){
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->applyFromArray([
                                'font' => [
                                    'name' => 'Arial',
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                ],
                            ]);
                            $count++;
                        }

                        $footers = json_decode($format->footers);
                        if ($footers != null){
                            foreach ($footers as $footer){
                                $event->sheet->getStyle('A'.$count)->applyFromArray([
                                    'font' => [
                                        'name' => 'Arial',
                                    ]
                                ]);
                                $count++;
                            }
                        }
                        
                        $count += 2;
                    }
                }
                $count = $count + 2;
                if ($this->isFacultyRes != null) {
                    $event->sheet->setCellValue('A'.$count, 'Prepared By:');
                    $event->sheet->getStyle('A'.$count)->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'bold' => true, 
                            'size' => 14
                        ],
                    ]);
                    $count = $count + 5;
                    if ($this->signature != null) {
                        $path = storage_path('app/documents/'. $this->signature);
                        $coordinates = 'A'.$count-4;
                        $sheet = $event->sheet->getDelegate();
                        echo $this->addImage($path, $coordinates, $sheet);
                    } 
                    $event->sheet->setCellValue('A'.$count, $this->fr_name);
                    $event->sheet->getStyle('A'.$count)->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'bold' => true, 
                            'size' => 14
                        ],
                    ]);

                    $event->sheet->getStyle('A'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $count = $count + 1;
                    $event->sheet->setCellValue('A'.$count, 'Researcher, '.$this->departmentName);
                    $event->sheet->getStyle('A'.$count)->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'bold' => true, 
                            'size' => 14
                        ],
                    ]);
                    $event->sheet->getStyle('A'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                elseif ($this->isFacultyExt != null) {
                    $event->sheet->setCellValue('A'.$count, 'Prepared By:');
                    $event->sheet->getStyle('A'.$count)->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'bold' => true, 
                            'size' => 14
                        ],
                    ]);
                    $count = $count + 5;
                    if ($this->signature != null) {
                        $path = storage_path('app/documents/'. $this->signature);
                        $coordinates = 'A'.$count-4;
                        $sheet = $event->sheet->getDelegate();
                        echo $this->addImage($path, $coordinates, $sheet);
                    } 
                    $event->sheet->setCellValue('A'.$count, $this->fe_name);
                    $event->sheet->getStyle('A'.$count)->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'bold' => true, 
                            'size' => 14
                        ],
                    ]);

                    $event->sheet->getStyle('A'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                    $count = $count + 1;
                    $event->sheet->setCellValue('A'.$count, 'Extensionist, '.$this->departmentName);
                    $event->sheet->getStyle('A'.$count)->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'bold' => true, 
                            'size' => 14
                        ],
                    ]);
                    $event->sheet->getStyle('A'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                }
                else {
                    $event->sheet->setCellValue('A'.$count, 'Supporting Evidence Verified By:');
                    $event->sheet->setCellValue('E'.$count, 'Prepared By:');
                    $event->sheet->getStyle('A'.$count.':E'.$count)->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'bold' => true, 
                            'size' => 14
                        ],
                    ]);
                    $count = $count + 5;
                    /* SIGNATURE */
                    if ($this->fr_signature != null) {
                        $path = storage_path('app/documents/'. $this->fr_signature);
                        $coordinates = 'A'.$count-4;
                        $sheet = $event->sheet->getDelegate();
                        echo $this->addImage($path, $coordinates, $sheet);
                    } elseif ($this->fe_signature != null) {
                        $path = storage_path('app/documents/'. $this->fe_signature);
                        $coordinates = 'C'.$count-4;
                        $sheet = $event->sheet->getDelegate();
                        echo $this->addImage($path, $coordinates, $sheet);
                    }

                    if ($this->signature != null) {
                        $path = storage_path('app/documents/'. $this->signature);
                        $coordinates = 'E'.$count-4;
                        $sheet = $event->sheet->getDelegate();
                        echo $this->addImage($path, $coordinates, $sheet);
                    }
                    
                    /*  */
                    $event->sheet->setCellValue('A'.$count, $this->fr_name);
                    $event->sheet->setCellValue('C'.$count, $this->fe_name);
                    $event->sheet->setCellValue('E'.$count, $this->arranged_name);
                    $event->sheet->getStyle('A'.$count.':E'.$count)->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'bold' => true, 
                            'size' => 14
                        ],
                    ]);
                    $event->sheet->getStyle('A'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getStyle('C'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                    $event->sheet->getStyle('E'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
                    
                    $count = $count + 1;
                    $event->sheet->setCellValue('A'.$count, 'Researcher, '.$this->departmentName);
                    $event->sheet->setCellValue('C'.$count, 'Extensionist, '.$this->departmentName);
                    $event->sheet->setCellValue('E'.$count, 'Chairperson, '.$this->departmentName);
                    $event->sheet->getStyle('A'.$count.':E'.$count)->applyFromArray([
                        'font' => [
                            'name' => 'Arial',
                            'bold' => true, 
                            'size' => 14
                        ],
                    ]);
                }
                $event->sheet->getStyle('A'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('C'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('E'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $event->sheet->getStyle('A'.$count)->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('C'.$count)->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('E'.$count)->getAlignment()->setWrapText(true);
                
                $event->sheet->getStyle('A'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('C'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('E'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }

        ];
    }

    public function addImage($path, $coordinates, $sheet) {
        $signature = new Drawing();
        $signature->setPath($path);
        $signature->setCoordinates($coordinates);
        $signature->setWidth(30);
        $signature->setHeight(80);
        $signature->setWorksheet($sheet);
    }
}