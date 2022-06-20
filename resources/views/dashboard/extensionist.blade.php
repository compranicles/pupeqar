<div class="col-md-3 mb-4">
    <div class="d-flex rounded align-items-center shadow-sm px-3 py-3" style="background-color: #0055a9;">
        <!-- <div>
            <i class="bi bi-search home-icons" style="color: #0055a9;"></i>
        </div> -->
        <div class="db-text d-flex align-items-center">
            <p class="db-stat">{{ $countToReview }}</p>
            <a class="db-text" style="word-wrap: break-word;" href="{{ route('extensionist.index') }}">Extensions to Review ({{ $collegeCode }})</a>
            <!-- College code -->
        </div>
    </div>
</div>