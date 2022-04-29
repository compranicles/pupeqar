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


class IPOAccomplishmentReportExport implements FromView, WithEvents
{
    function __construct($type, $q1, $q2, $year){
        $this->type = $type;
        $this->q1 = $q1;
        $this->q2 = $q2;
        $this->year = $year;
    }

    public function view(): View {
        $table_format = [];
        $table_columns = [];
        $table_contents = [];

        if($this->type == "academic") {
            //get the table names
            $table_format = GenerateTable::where('type_id', 2)->get();
        }
        elseif ($this->type == 'admin') {
            $table_format = GenerateTable::where('type_id', 1)->get();
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
            else
                $table_contents[$format->id] =
                Report::select('reports.*', 
                    DB::raw("CONCAT(COALESCE(users.last_name, ''), ', ', COALESCE(users.first_name, ''), ' ', COALESCE(users.middle_name, ''), ' ', COALESCE(users.suffix, '')) as faculty_name"),
                        'sectors.name as sector_name',
                        'departments.name as department_name',
                        'colleges.name as college_name'
                    )->where('reports.report_category_id', $format->report_category_id)
                    ->where('reports.ipqmso_approval', 1)
                    ->where('reports.report_year', $this->year)
                    ->whereBetween('reports.report_quarter', [$this->q1, $this->q2])
                    ->join('users', 'users.id', 'reports.user_id')
                    ->join('sectors', 'sectors.id', 'reports.sector_id')
                    ->join('departments', 'departments.id', 'reports.sector_id')
                    ->join('colleges', 'colleges.id', 'reports.sector_id')
                    ->orderBy('reports.report_quarter', 'ASC')
                    ->orderBy('sector_name', 'ASC')
                    ->orderBy('college_name', 'ASC')
                    ->orderBy('department_name', 'ASC')
                    ->orderBy('faculty_name', 'ASC')
                    ->get()->toArray();
        }

        $this->table_format = $table_format;
        $this->table_columns = $table_columns;
        $this->table_contents = $table_contents;

        $type = $this->type;
        $q1 = $this->q1;
        $q2 = $this->q2;
        $year = $this->year;
        return view('reports.generate.ipo-output', compact('table_format', 'table_columns', 'table_contents', 'type', 'year', 'q1', 'q2'));

    }


    public function registerEvents(): array {
        return [];
    }
}
