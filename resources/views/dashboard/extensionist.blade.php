<!-- row (dashboard.blade.php) -->
<div class="row">
    <div class="col-md-3">
        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px;">
            <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
                <div class="db-icon">
                    <i class="bi bi-send"></i>
                </div>
                <div class="db-text ml-auto">
                    <h5 class="text-right">Quarter {{ isset($currentQuarterYear->current_quarter) ? $currentQuarterYear->current_quarter : '' }} of {{ isset($currentQuarterYear->current_year) ? $currentQuarterYear->current_year : '' }}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="db-card bg-body people-icon rounded shadow-sm" style="background-color: white; padding-top: 10px; border-bottom: 2px solid #FDB858;">
            <div class="p-3">
                <h3 class="text-center">{{ $arrayOfNoOfAllUsers['faculty'] }}</h3>
                <h6 class="text-center">Faculty Employees in {{ $extensionistDepartment[0]->name }}</h6>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px; border-bottom: 2px solid #EA676D;">
            <div class="p-3">
                <h3 class="text-center">{{ $arrayOfNoOfAllUsers['admin'] }}</h3>
                <h6 class="text-center">Administrative Employees in {{ $extensionistDepartment[0]->name }}</h6>
            </div>
        </div>
    </div>
</div>
<div class="div-bottom-separator"></div>

<!-- Graphs (Colleges and departments added in profile-->
<!-- row ended -->