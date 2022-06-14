<div class="col-md-3 mb-4">
    <div class="d-flex rounded align-items-center shadow-sm px-3 py-3" style="background-color: #da9101;">
        <!-- <div>
            <i class="bi bi-file-earmark-bar-graph home-icons" style="color: #da9101;"></i>
        </div> -->
        <div class="db-text d-flex align-items-center">
            <p class="db-stat">{{ $countAccomplishmentsSubmitted }}</p>
            <a class="db-text" style="word-wrap: break-word;" href="{{ route('reports.consolidate.myaccomplishments') }}">Accomplishments You Submitted</a>
        </div>
    </div>
</div>

<div class="col-md-3 mb-4">
    <div class="d-flex rounded align-items-center shadow-sm px-3 py-3" style="background-color: #da9101;">
        <!-- <div>
            <i class="bi bi-file-earmark-arrow-down home-icons" style="color: #da9101;"></i>
        </div> -->
        <div class="db-text d-flex align-items-center">
            <p class="db-stat">{{ $countAccomplishmentsReturned }}</p>
            <a class="db-text" style="word-wrap: break-word;" href="{{ route('reports.consolidate.myaccomplishments') }}">Accomplishments Returned</a>
        </div>
    </div>
</div>