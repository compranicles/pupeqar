<div class="col-md-3 mb-4 ">
    <div class="d-block rounded align-items-center shadow-sm px-3 py-3" style="background-color: #0055a9;">
        <!-- <div>
            <i class="bi bi-file-bar-graph home-icons" style="color: #0055a9;"></i>
        </div> -->
        <div class="db-text d-flex align-items-center">
            <p class="db-text db-stat">{{ $countToReview }}</p>
            <a class="db-text" style="word-wrap: break-word;" href="{{ route('ipo.index') }}">Accomplishments to Review (IPO)</a>
        </div>
    </div>
</div>

<div class="col-md-3 mb-4 ">
    <div class="d-block rounded align-items-center shadow-sm px-3 py-3" style="background-color: #0055a9;">
        <!-- <div>
            <i class="bi bi-file-bar-graph home-icons" style="color: #0055a9;"></i>
        </div> -->
        <div class="db-text d-flex align-items-center">
            <p class="db-stat">{{ $countReceived }}/{{ $countExpectedTotal }}</p>
            <small><a class="db-text" style="word-wrap: break-word;" href="{{ route('ipo.index') }}">Received Over Expected Total of Accomplishments (IPO)</a></small>
        </div>
    </div>
</div>