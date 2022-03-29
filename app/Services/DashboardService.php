<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\Report;

class DashboardService {
    public function countAccomplishmentByOfficerAndDepartmentAndStatusAndQuarterYear($officialApprovalColumn, $department, $status, $currentQuarterYear) { 
        if ($status == 0) {
            $countAccomplishment = Report::whereNull($officialApprovalColumn)
                ->where('department_id', $department)
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
        } else {
            $countAccomplishment = Report::whereNotNull($officialApprovalColumn)
                ->where('department_id', $department)
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
        }
        return $countAccomplishment;
    }

    public function countAccomplishmentByOfficerAndDepartmentAndStatusAndQuarterYearAndReportCategoryID($officialApprovalColumn, $department, $status, $currentQuarterYear, $reportCategoryID) {
        if ($status == 0) {
            $countAccomplishment = Report::whereNull($officialApprovalColumn)
                ->where('department_id', $department)
                ->whereIn('report_category_id', [$reportCategoryID])
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
        } else {
            $countAccomplishment = Report::whereNotNull($officialApprovalColumn)
                ->where('department_id', $department)
                ->whereIn('report_category_id', [$reportCategoryID])
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
        }

        return $countAccomplishment;
    }

    // No report categories (get all)
    public function countAccomplishmentByOfficerAndDepartmentAndQuarterYear($officialApprovalColumn, $department, $currentQuarterYear) {
        $countAccomplishment = Report::whereNotNull($officialApprovalColumn)
                ->where('department_id', $department)
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
        return $countAccomplishment;
    }

    // No report categories (get all)
    public function countAccomplishmentByOfficerAndCollegeAndQuarterYear($officialApprovalColumn, $college, $currentQuarterYear) {
        $countAccomplishment = Report::whereNotNull($officialApprovalColumn)
                ->where('college_id', $college)
                ->where('report_quarter', $currentQuarterYear->current_quarter)
                ->where('report_year', $currentQuarterYear->current_year)
                ->count();
        return $countAccomplishment;
    }

    public function countEmployeesBySectorID($sectorID) {
        $countEmployees = Employee::where('sector_id', $sectorID)->count();
        return $countEmployees;
    }
}