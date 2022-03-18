<!-- row (dashboard.blade.php) -->
<div class="col">
        <div class="db-card bg-body people-icon rounded shadow-sm" style="background-color: white; padding-top: 10px; border-bottom: 2px solid #FDB858;">
            <div class="p-3">
                <h3 class="text-center">{{ $arrayOfNoOfAllUsers['faculty'] }}</h3>
                <h6 class="text-center">Faculty Employees in {{ $chairpersonDepartment[0]->name }}</h6>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 10px; border-bottom: 2px solid #EA676D;">
            <div class="p-3">
                <h3 class="text-center">{{ $arrayOfNoOfAllUsers['admin'] }}</h3>
                <h6 class="text-center">Administrative Employees in {{ $chairpersonDepartment[0]->name }}</h6>
            </div>
        </div>
    </div>
</div>
<!-- Graphs (Colleges and departments added in profile-->
<div class="row div-top-separator">

</div>
<!-- row ended -->