<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-file-earmark-bar-graph home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-center">{{ $countAccomplishmentsSubmitted }}</h4>
                <a class="home-card-links" href="{{ route('reports.consolidate.myaccomplishments') }}">Accomplishments Submitted</a>
            </div>
        </div>
    </div>
</div>
<div class="col-md-4">
    <div class="db-card bg-body rounded shadow-sm" style="background-color: white; padding-top: 8px;">
        <div class="d-flex" style="padding: 2.40em 2em 2.40em 2em">
            <div class="db-icon">
                <i class="bi bi-file-earmark-arrow-down home-icons"></i>
            </div>
            <div class="ml-auto">
                <h4 class="text-center">{{ $countAccomplishmentsReturned }}</h4>
                <a class="home-card-links" href="{{ route('reports.consolidate.myaccomplishments') }}">Accomplishments Returned</a>
            </div>
        </div>
    </div>
</div>