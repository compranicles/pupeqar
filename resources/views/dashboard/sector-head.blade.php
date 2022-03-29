@if ($sector['sector_id'] == 3)
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-people home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-right">{{ $arrayOfNoOfAllUsers['faculty'] }}</h4>
                <p>Total No. of Faculty Employees</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-building home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-right">{{ $college }}</h4>
                <p>Total No. of Colleges in the Sector</p>
            </div>
        </div>
    </div>
</div>
@elseif ($sector['sector_id'] == 4)
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-people home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-right">{{ $arrayOfNoOfAllUsers['admin'] }}</h4>
                <p>Total No. of Admin Employees</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-building home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-right">{{ $college }}</h4>
                <p>Total No. of Offices in the Sector</p>
            </div>
        </div>
    </div>
</div>
@elseif ($sector['sector_id'] == 8)
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-people home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-right">{{ $arrayOfNoOfAllUsers['faculty'] }}</h4>
                <p>Total No. of Faculty Employees</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-people home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-right">{{ $arrayOfNoOfAllUsers['admin'] }}</h4>
                <p>Total No. of Admin Employees</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white;">
        <div style="padding: 1.70em 2em 2.40em 2em">
            <div class="text-center mb-3">
                <a href="{{ route('sector.index') }}"><i class="bi bi-file-bar-graph text-center home-navigate-icons"></i></a>
            </div>
            <h6 class="text-center"><a href="{{ route('sector.index') }}" class="home-card-links">Review Accomplishments</a></h6>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white;">
        <div style="padding: 1.70em 2em 2.40em 2em">
            <div class="text-center mb-3">
                <a href="{{ route('reports.consolidate.sector', $sector['sector_id']) }}"><i class="bi bi-file-earmark-check text-center home-navigate-icons"></i></a>
            </div>
            <h6 class="text-center"><a href="{{ route('reports.consolidate.sector', $sector['sector_id']) }}" class="home-card-links">Consolidate College/Branch/Campus/Office Accomplishments</a></h6>
        </div>
    </div>
</div>
@endif