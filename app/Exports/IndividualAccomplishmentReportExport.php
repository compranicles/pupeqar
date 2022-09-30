<?php

namespace App\Exports;

use App\Http\Controllers\Controller;
use App\Models\{
    User,
    Report,
};
use App\Models\Maintenance\{
    College,
    GenerateTable,
    GenerateColumn,
    Department,
};
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use App\Services\NameConcatenationService;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class IndividualAccomplishmentReportExport implements FromView, WithEvents
{
    function __construct($level, $type, $yearGenerate, $quarterGenerate,
    $cbco, $userID, $getCollege, $getSector, $director, $sectorHead) {
        $this->level = $level;
        $this->type = $type;
        $this->yearGenerate = $yearGenerate;
        $this->quarterGenerate = $quarterGenerate;
        $this->cbco = $cbco;
        $this->userID = $userID;
        $this->getCollege = $getCollege;
        $this->getSector = $getSector;
        $this->director = $director;
        $this->sectorHead = $sectorHead;

        $user = User::where('id', auth()->id())->first();
        $this->signature = $user->signature;
        $this->arrangedName = (new NameConcatenationService())->getConcatenatedNameByUserAndRoleName($user, " ");
        $this->directorName = (new NameConcatenationService())->getConcatenatedNameByUserAndRoleName($director, "Dean/Director");
        $this->sectorHeadName = (new NameConcatenationService())->getConcatenatedNameByUserAndRoleName($sectorHead, "Sector Head");

        if ($this->director != null) {
            $this->college = $this->getCollege->name;
        }

        if ($this->sectorHead != null) {
            $this->sector = $this->getSector->name;
        }

        $this->user = $user;
    }

    public function view(): View
    {
        $tableFormat;
        $tableColumns;
        $tableContents;
        $data;
        // $source_type;

        if($this->type == "academic"){
            if($this->level == "individual"){
                $data = User::where('id', $this->userID)->select('users.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as name"))->first();
                $tableFormat = GenerateTable::where('type_id', 2)->where('is_individual', '!=',  0)->get();
                $tableColumns = [];
                foreach ($tableFormat as $format){
                    if($format->is_table == "0")
                        $tableColumns[$format->id] = [];
                    else
                        $tableColumns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }

                $tableContents = [];
                foreach ($tableFormat as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $tableContents[$format->id] = [];
                    else
                        $tableContents[$format->id] = Report::
                            // ->where('user_roles.role_id', 1)
                            whereIn('reports.format', ['f', 'x'])
                            ->where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.report_year', $this->yearGenerate)
                            ->where('reports.report_quarter', $this->quarterGenerate)
                            ->where('reports.user_id', $this->userID)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->where('reports.college_id', $this->cbco)
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                }
            }
        }
        elseif($type == "admin"){
            if($level == "individual"){
                // $source_type = "individual";
                $data = User::where('id', $this->userID)->select('users.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as name"))->first();
                $tableFormat = GenerateTable::where('type_id', 1)->where('is_individual', '!=',  0)->get();
                $tableColumns = [];
                foreach ($tableFormat as $format){
                    if($format->is_table == "0")
                        $tableColumns[$format->id] = [];
                    else
                        $tableColumns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
                }

                $tableContents = [];
                foreach ($tableFormat as $format){
                    if($format->is_table == "0" || $format->report_category_id == null)
                        $tableContents[$format->id] = [];
                    else
                        $tableContents[$format->id] = Report::
                            // ->where('user_roles.role_id', 1)
                            whereIn('reports.format', ['a', 'x'])
                            ->where('reports.report_category_id', $format->report_category_id)
                            ->where('reports.report_year', $this->yearGenerate)
                            ->where('reports.report_quarter', $this->quarterGenerate)
                            ->where('reports.user_id', $this->userID)
                            ->where('reports.college_id', $this->cbco)
                            ->join('users', 'users.id', 'reports.user_id')
                            ->select('reports.*', DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"))
                            ->get()->toArray();
                        }
                    }
                }

        $this->tableFormat = $tableFormat;
        $this->tableColumns = $tableColumns;
        $this->tableContents = $tableContents;
        $level = $this->level;
        $type = $this->type;
        $yearGenerate = $this->yearGenerate;
        $quarterGenerate = $this->quarterGenerate;
        $userID = $this->userID;
        $user = $this->user;
        $director = $this->director;
        $sectorHead = $this->sectorHead;

        return view('reports.generate.example', compact('tableFormat', 'tableColumns', 'tableContents', 'data', 'type', 'level', 'yearGenerate', 'quarterGenerate', 'userID', 'user', 'director', 'sectorHead'));
    }

    public function registerEvents(): array {
        return [
            AfterSheet::class => function(Aftersheet $event) {
                $event->sheet->getSheetView()->setZoomScale(70);
                $event->sheet->getStyle('A1:Z500')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Helvetica');
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize(12);
                $event->sheet->getDefaultColumnDimension()->setWidth(33);
                $event->sheet->freezePane('C1');
                $event->sheet->mergeCells('A1:G1');
                if ($this->level == "individual") {
                    if ($this->type == "academic")
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

                $event->sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

                $event->sheet->mergeCells('B2:C2');
                if ($this->level == "individual") {
                    if ($this->type == "academic")
                        $event->sheet->setCellValue('B2', 'COLLEGE/BRANCH/CAMPUS:');
                    elseif ($this->type == "admin")
                        $event->sheet->setCellValue('B2', 'OFFICE:');
                }

                $event->sheet->getStyle('B2')->applyFromArray([
                    'font' => [
                        'size' => 16,
                        'bold' => true,
                    ]
                ]);
                $college = College::where('id', $this->cbco)->select('name')->first();
                $event->sheet->getStyle('B2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('D2:F2');
                $event->sheet->setCellValue('D2', $college->name);
                $event->sheet->getStyle('D2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('D2:F2')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('D2')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);

                // Name
                $event->sheet->mergeCells('B3:C3');
                $event->sheet->setCellValue('B3', 'EMPLOYEE:');
                $event->sheet->getStyle('B3')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('B3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->mergeCells('D3:F3');
                $event->sheet->setCellValue('D3', $this->arrangedName);
                $event->sheet->getStyle('D3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('D3:F3')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('D3')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);

                $event->sheet->mergeCells('B4:C4');
                $event->sheet->setCellValue('B4', 'QUARTER:');
                $event->sheet->getStyle('B4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('B4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->setCellValue('D4', $this->quarterGenerate);
                $event->sheet->getStyle('D4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('D4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('D4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $event->sheet->setCellValue('E4', 'CALENDAR YEAR:');
                $event->sheet->getStyle('E4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('E4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->setCellValue('F4', $this->yearGenerate);
                $event->sheet->getStyle('F4')->applyFromArray([
                    'font' => [
                        'size' => 16,
                    ]
                ]);
                $event->sheet->getStyle('F4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('F4')->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $count = 7;
                $tableFormat = $this->tableFormat;
                $tableColumns = $this->tableColumns;
                $tableContents = $this->tableContents;
                foreach($tableFormat as $format) {
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
                        $length = count($tableColumns[$format->id]);
                        if ($length == null){
                            $length = 2;
                        }
                        else{
                            $length = $length+2;
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
                        $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FF203764");
                        $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_WHITE);
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
                        foreach($tableContents[$format->id] as $contents){
                            //DOCUMENTS LINK
                            $documents =  json_decode($contents['report_documents'], true);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFD9E1F2");
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->applyFromArray([
                                'font' => [
                                    'name' => 'Arial',
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['argb' => 'FF515256'],
                                    ],
                                ],
                            ]);
                            $count++;
                        }

                        if($tableContents[$format->id] == null){
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFD9E1F2");
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFont()->getColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_BLACK);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->applyFromArray([
                                'font' => [
                                    'name' => 'Arial',
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                        'color' => ['argb' => 'FF515256'],
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
                $event->sheet->setCellValue('A'.$count, 'Prepared By:');
                $event->sheet->setCellValue('C'.$count, 'Certified Correct:');
                $event->sheet->setCellValue('E'.$count, 'Approved By:');
                $event->sheet->getStyle('A'.$count.':E'.$count)->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'bold' => true,
                        'size' => 14
                    ],
                ]);

                $count = $count + 5;
                /* SIGNATURE */
                if ($this->signature != null) {
                    $employeeSignature = new Drawing();
                    $employeeSignature->setName('');
                    $employeeSignature->setDescription('Employee Signature');
                    $employeeSignature->setPath(storage_path('app/documents/'. $this->signature));
                    $employeeSignature->setWidth(30);
                    $employeeSignature->setHeight(80);
                    $employeeSignature->setCoordinates('A'.$count-4);
                    $employeeSignature->setWorksheet($event->sheet->getDelegate());
                }
                /*  */
                $event->sheet->setCellValue('A'.$count, $this->arrangedName);
                $event->sheet->setCellValue('C'.$count, $this->directorName);
                $event->sheet->setCellValue('E'.$count, $this->sectorHeadName);
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
                $event->sheet->setCellValue('A'.$count, 'Employee');
                if ($this->director != null) {
                    $event->sheet->setCellValue('C'.$count, 'Dean/Director, '.$this->college);
                } else {
                    $event->sheet->setCellValue('C'.$count, 'Dean/Director');
                }

                if ($this->sectorHead != null) {
                    $event->sheet->setCellValue('E'.$count, 'VP, '.$this->sector);
                } else {
                    $event->sheet->setCellValue('E'.$count, 'Sector Head');
                }
                $event->sheet->getStyle('A'.$count.':E'.$count)->applyFromArray([
                    'font' => [
                        'name' => 'Arial',
                        'bold' => true,
                        'size' => 14
                    ],
                ]);

                $event->sheet->getStyle('A'.$count)->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('C'.$count)->getAlignment()->setWrapText(true);
                $event->sheet->getStyle('E'.$count)->getAlignment()->setWrapText(true);

                $event->sheet->getStyle('A'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('C'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $event->sheet->getStyle('E'.$count)->getBorders()->getTop()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                $event->sheet->getStyle('A'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('C'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $event->sheet->getStyle('E'.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        ];
    }


}
