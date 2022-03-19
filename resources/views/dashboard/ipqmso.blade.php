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
                <h6 class="text-center">Faculty Employees</h6>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px; border-bottom: 2px solid #EA676D;">
            <div class="p-3">
                <h3 class="text-center">{{ $arrayOfNoOfAllUsers['admin'] }}</h3>
                <h6 class="text-center">Administrative</h6>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px; border-bottom: 2px solid #62CAE4;">
            <div class="p-3">
                <h3 class="text-center">{{ $arrayOfNoOfAllUsers['researcher'] }}</h3>
                <h6 class="text-center">Researchers</h6>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px; border-bottom: 2px solid #7D8EE2;">
            <div class="p-3">
                <h3 class="text-center">{{ $arrayOfNoOfAllUsers['extensionist'] }}</h3>
                <h6 class="text-center">Extensionists</h6>
            </div>
        </div>
    </div>
</div>
<div class="row div-bottom-separator" style="margin-top: 0.5em;">
    <div class="col">
        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px; border-bottom: 2px solid #62CAE4;">
            <div class="p-3">
                <h3 class="text-center">{{ $arrayOfNoOfAllUsers['chairperson'] }}</h3>
                <h6 class="text-center">Chairpeople</h6>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px; border-bottom: 2px solid #FDB858;">
            <div class="p-3">
                <h3 class="text-center">{{ $arrayOfNoOfAllUsers['director'] }}</h3>
                <h6 class="text-center">Directors</h6>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px; border-bottom: 2px solid #EA676D;">
            <div class="p-3">
                <h3 class="text-center">{{ $arrayOfNoOfAllUsers['ipo'] }}</h3>
                <h6 class="text-center">IPO Employees</h6>
            </div>
        </div>
    </div>
</div>
<div class="div-bottom-separator"></div>

<!-- row ended -->