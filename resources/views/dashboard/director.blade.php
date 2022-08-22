<!-- <div class="col-md-3 mb-4">
    <div class="d-flex rounded align-items-center shadow-sm px-3 py-3" style="background-color: #0055a9;">
        <div class="db-text d-flex align-items-center">
            <p class="db-stat">{{ $countToReview }}</p>
            <a class="db-text" style="word-wrap: break-word;" href="{{ route('director.index') }}">Accomplishments to Review (Dean/Director - {{ $collegeCode }})</a>
     
        </div>
    </div>
</div> -->

<div class="data-card-wide shadow-sm" style="background-color: #7c5b8c; border-left: 3px solid white;">
    <div class="db-text">
        <p class="db-stat">{{ $countToReview }}*</p>
        <a class="db-text" style="word-wrap: break-word;" href="{{ route('director.index') }}">TO REVIEW ({{ $collegeCode }})</a>
        <br>
        <small>*Reflected on Associate/ Assistant to Dean/ Director</small>
    </div>
</div>
        