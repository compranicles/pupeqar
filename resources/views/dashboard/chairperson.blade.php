<!-- <div class="col-md-3 mb-4">
    <div class="d-flex rounded align-items-center shadow-sm px-3 py-3" style="background-color: #0055a9;">
        <div class="db-text d-flex align-items-center">
            <p class="db-stat">{{ $countToReview }}</p>
            <a class="db-text" style="word-wrap: break-word;" href="{{ route('chairperson.index') }}">Accomplishments to Review (Chair/Chief - {{ $departmentCode }})</a>
        </div>
    </div>
</div> -->

<div class="data-card shadow-sm" style="background-color: #5679bf; border-left: 3px solid white;">
    <div class="db-text">
        <p class="db-stat">{{ $countToReview }}</p>
        <a class="db-text" style="word-wrap: break-word;" href="{{ route('chairperson.index') }}">TO REVIEW ({{ $departmentCode }})</a>
    </div>
</div>
        
