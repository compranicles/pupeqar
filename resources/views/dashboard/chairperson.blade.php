<div class="col-md-3 mb-4">
    <div class="d-flex rounded align-items-center shadow-sm px-3 py-3" style="background-color: #0055a9;">
        <!-- <div>
            <i class="bi bi-file-bar-graph home-icons" style="color: #0055a9;"></i>
        </div> -->
        <div class="db-text d-flex align-items-center">
            <p class="db-stat">{{ $countToReview }}</p>
            <a class="db-text" style="word-wrap: break-word;" href="{{ route('chairperson.index') }}">Accomplishments to Review (Dept - {{ $department_code }})</a>
            <!-- College code -->
        </div>
    </div>
</div>