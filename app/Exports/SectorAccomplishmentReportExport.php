<?php

namespace App\Exports;

use App\Models\Report;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\Maintenance\GenerateTable;
use App\Models\Maintenance\GenerateColumn;
use App\Services\NameConcatenationService;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class SectorAccomplishmentReportExport implements FromView, WithEvents
{
    function __construct($type, $q1, $q2, $year, $sector, $asked){
        $this->type = $type;
        $this->q1 = $q1;
        $this->q2 = $q2;
        $this->year = $year;
        $this->sector = $sector;
        $this->asked = $asked;
    }

    public function view(): View {
        $table_format = [];
        $table_columns = [];
        $table_contents = [];
        $employee_type = [];

        if($this->type == "academic") {
            //get the table names
            $table_format = GenerateTable::whereIn('type_id', [2,4])->get();
            $employee_type = ['f', 'x'];
        }
        elseif ($this->type == 'admin') {
            $table_format = GenerateTable::whereIn('type_id', [1,4])->get();
            $employee_type = ['a', 'x'];
        }

        //get the table columns/headers
        foreach ($table_format as $format) {
            if ($format->is_table == "0")
                $table_columns[$format->id] = [];
            else
                $table_columns[$format->id] = GenerateColumn::where('table_id', $format->id)->orderBy('order')->get()->toArray();
        }

        //get the accomplishment for each table
        foreach ($table_format as $format) {
            if ($format->is_table == "" || $format->report_category_id == null)
                $table_contents[$format->id] = [];
            else {
                if($this->asked = 'ipo')
                    if ($format->type_id != 4) {
                        $table_contents[$format->id] =
                            Report::
                                whereIn('reports.format', $employee_type)
                                ->select('reports.*',
                                DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"),
                                    'sectors.name as sector_name',
                                    'departments.name as department_name',
                                    'colleges.name as college_name'
                                )->where('reports.report_category_id', $format->report_category_id)
                                ->where('reports.sector_id', $this->sector->id)
                                ->whereIn('reports.sector_approval', [1,2])
                                ->where('reports.report_year', $this->year)
                                ->whereBetween('reports.report_quarter', [$this->q1, $this->q2])
                                ->join('users', 'users.id', 'reports.user_id')
                                ->join('sectors', 'sectors.id', 'reports.sector_id')
                                ->join('departments', 'departments.id', 'reports.department_id')
                                ->join('colleges', 'colleges.id', 'reports.college_id')
                                ->orderBy('reports.report_quarter', 'ASC')
                                ->orderBy('college_name', 'ASC')
                                ->orderBy('department_name', 'ASC')
                                ->orderBy('faculty_name', 'ASC')
                                ->get()->toArray();
                    } else {
                        $table_contents[$format->id] =
                            Report::
                                select('reports.*',
                                DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"),
                                    'sectors.name as sector_name',
                                    'departments.name as department_name',
                                    'colleges.name as college_name'
                                )->where('reports.report_category_id', $format->report_category_id)
                                ->where('reports.sector_id', $this->sector->id)
                                ->whereIn('reports.sector_approval', [1,2])
                                ->where('reports.report_year', $this->year)
                                ->whereBetween('reports.report_quarter', [$this->q1, $this->q2])
                                ->join('users', 'users.id', 'reports.user_id')
                                ->join('sectors', 'sectors.id', 'reports.sector_id')
                                ->join('departments', 'departments.id', 'reports.department_id')
                                ->join('colleges', 'colleges.id', 'reports.college_id')
                                ->orderBy('reports.report_quarter', 'ASC')
                                ->orderBy('college_name', 'ASC')
                                ->orderBy('department_name', 'ASC')
                                ->orderBy('faculty_name', 'ASC')
                                ->get()->toArray();
                    }
                elseif($this->asked = 'sector') {
                    $table_contents[$format->id] =
                        Report::
                        whereIn('reports.format', $employee_type)
                        ->select('reports.*',
                            DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"),
                                'sectors.name as sector_name',
                                'departments.name as department_name',
                                'colleges.name as college_name'
                            )->where('reports.report_category_id', $format->report_category_id)
                            ->whereIn('reports.sector_approval', [1,2])
                            ->where('reports.sector_id', $this->sector->id)
                            ->where('reports.report_year', $this->year)
                            ->whereBetween('reports.report_quarter', [$this->q1, $this->q2])
                            ->join('users', 'users.id', 'reports.user_id')
                            ->join('sectors', 'sectors.id', 'reports.sector_id')
                            ->join('departments', 'departments.id', 'reports.department_id')
                            ->join('colleges', 'colleges.id', 'reports.college_id')
                            ->orderBy('reports.report_quarter', 'ASC')
                            ->orderBy('college_name', 'ASC')
                            ->orderBy('department_name', 'ASC')
                            ->orderBy('faculty_name', 'ASC')
                            ->get()->toArray();
                }
            }
        }

        $this->table_format = $table_format;
        $this->table_columns = $table_columns;
        $this->table_contents = $table_contents;

        $type = $this->type;
        $q1 = $this->q1;
        $q2 = $this->q2;
        $year = $this->year;
        $sector = $this->sector;
        return view('reports.generate.sector-output', compact('table_format', 'table_columns', 'table_contents', 'type', 'year', 'q1', 'q2', 'sector'));

    }


    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getSheetView()->setZoomScale(70);
                $event->sheet->getStyle('A1:Z500')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setName('Arial');
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->getFont()->setSize(12);
                $event->sheet->getDefaultColumnDimension()->setWidth(33);
                $event->sheet->freezePane('C1');

                $event->sheet->mergeCells('A1:P1');

                $count = 3;
                $table_format = $this->table_format;
                $table_columns = $this->table_columns;
                $table_contents = $this->table_contents;
                foreach ($table_format as $format) {

                    if ($format->is_table == '1') {

                        //columns
                        $columnTWO = Coordinate::stringFromColumnIndex(2);
                        $length = count($table_columns[$format->id]);
                        if ($length == null){
                            $length = 4;
                        }
                        else{
                            $length = $length+6;
                        }
                        $letter = Coordinate::stringFromColumnIndex($length);

                        if($format->name != ''){
                            $event->sheet->mergeCells('A'.$count.':'.$letter.$count);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFFFC000");
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFont()->getColor()->setARGB('FFC00000');
                            $event->sheet->getRowDimension($count)->setRowHeight(30);
                            $count++;

                            $event->sheet->mergeCells('A'.$count.':'.$letter.$count);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFFFC000");
                            $event->sheet->getStyle('A'.$count.':'.$letter.$count)->getFont()->getColor()->setARGB('FFC00000');
                            $event->sheet->getRowDimension($count)->setRowHeight(30);
                            $count++;
                        }

                        $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->getAlignment()->setWrapText(true);
                        $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFFDE9D9");
                        $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->applyFromArray([
                            'font' => [
                                'name' => 'Arial',
                                'bold' => true,
                                'size' => 14
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                ],
                            ],
                        ]);

                        $columnTHREE = Coordinate::stringFromColumnIndex(3);
                        $event->sheet->getStyle( $columnTHREE.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                        $event->sheet->getStyle( $columnTHREE.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                        $event->sheet->getStyle( $columnTHREE.$count.':'.$letter.$count)->applyFromArray([
                            'font' => [
                                'name' => 'Arial',
                                'bold' => true,
                                'size' => 14
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                ],
                            ],
                        ]);
                        $count++;

                        //contents
                        foreach($table_contents[$format->id] as $contents){
                            $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFFDE9D9");
                            $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->applyFromArray([
                                'font' => [
                                    'name' => 'Arial',
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                ],
                            ]);

                            $event->sheet->getStyle($columnTHREE.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle($columnTHREE.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle($columnTHREE.$count.':'.$letter.$count)->applyFromArray([
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
                            $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB("FFFDE9D9");
                            $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle('A'.$count.':'.$columnTWO.$count)->applyFromArray([
                                'font' => [
                                    'name' => 'Arial',
                                ],
                                'borders' => [
                                    'allBorders' => [
                                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    ],
                                ],
                            ]);

                            $event->sheet->getStyle($columnTHREE.$count.':'.$letter.$count)->getAlignment()->setWrapText(true);
                            $event->sheet->getStyle($columnTHREE.$count.':'.$letter.$count)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                            $event->sheet->getStyle($columnTHREE.$count.':'.$letter.$count)->applyFromArray([
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
                        else
                            $count++;

                        $count += 1;
                    }
                }
            }
        ];
    }
}
