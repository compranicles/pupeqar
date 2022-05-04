<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-people home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-center">{{ $arrayOfNoOfAllUsers[8]['faculty'] }}</h4>
                <p>Faculty Employees</p>
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
                <h4 class="text-center">{{ $arrayOfNoOfAllUsers[8]['admin'] }}</h4>
                <p>Admin Employees</p>
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
                <h4 class="text-center">{{ $arrayOfNoOfAllUsers[8]['chairperson'] }}</h4>
                <p>Chairpeople</p>
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
                <h4 class="text-center">{{ $arrayOfNoOfAllUsers[8]['director'] }}</h4>
                <p>Directors</p>
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
                <h4 class="text-center">{{ $arrayOfNoOfAllUsers[8]['sectorHead'] }}</h4>
                <p>Sector Heads</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white;">
        <div style="padding: 1.70em 2em 2.40em 2em">
            <div class="text-center mb-3">
                <a href="{{ route('ipo.index') }}"><i class="bi bi-file-bar-graph text-center home-navigate-icons"></i></a>
            </div>
            <h6 class="text-center"><a href="{{ route('ipo.index') }}" class="home-card-links">Receive Accomplishments</a></h6>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white;">
        <div style="padding: 1.70em 2em 2.40em 2em">
            <div class="text-center mb-3">
                <a href="{{ route('reports.consolidate.ipqmso') }}"><i class="bi bi-file-earmark-check text-center home-navigate-icons"></i></a>
            </div>
            <h6 class="text-center"><a href="{{ route('reports.consolidate.ipqmso') }}" class="home-card-links">Consolidate Accomplishments</a></h6>
        </div>
    </div>
</div>