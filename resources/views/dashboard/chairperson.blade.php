<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white;">
        <div style="padding: 1.70em 2em 2.40em 2em">
            <div class="text-center mb-3">
                <a href="{{ route('to-finalize.index') }}"><i class="bi bi-send-plus text-center home-navigate-icons"></i></a>
            </div>
            <h6 class="text-center"><a href="{{ route('to-finalize.index') }}" class="home-card-links">Submit Department Level Accomplishments</a></h6>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white;">
        <div style="padding: 1.70em 2em 2.40em 2em">
            <div class="text-center mb-3">
                <a href="{{ route('chairperson.index') }}"><i class="bi bi-file-bar-graph text-center home-navigate-icons"></i></a>
            </div>
            <h6 class="text-center"><a href="{{ route('chairperson.index') }}" class="home-card-links">Review Accomplishments</a></h6>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white;">
        <div style="padding: 1.70em 2em 2.40em 2em">
            <div class="text-center mb-3">
                <a href="{{ route('reports.consolidate.department', $department_id) }}"><i class="bi bi-file-earmark-check text-center home-navigate-icons"></i></a>
            </div>
            <h6 class="text-center"><a href="{{ route('reports.consolidate.department', $department_id) }}" class="home-card-links">Consolidate Department Accomplishments</a></h6>
        </div>
    </div>
</div>