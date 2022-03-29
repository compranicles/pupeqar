<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-search home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-right">{{ $countReviewed1 }}</h4>
                <p>Total No. of Reviewed Researches</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-gear home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-right">{{ $countReviewed2 }}</h4>
                <p>Total No. of Reviewed Invention</p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white;">
        <div style="padding: 1.70em 2em 2.40em 2em">
            <div class="text-center mb-3">
                <a href="{{ route('researcher.index') }}"><i class="bi bi-file-bar-graph text-center home-navigate-icons"></i></a>
            </div>
            <h6 class="text-center"><a href="{{ route('researcher.index') }}" class="home-card-links">Review Accomplishments</a></h6>
        </div>
    </div>
</div>