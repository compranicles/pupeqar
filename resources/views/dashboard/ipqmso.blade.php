
<div class="data-card shadow-sm" style="background-color: #718c54; border-left: 3px solid white;">
    <div class="db-text">
        <p class="db-stat">{{ $countToReview }}</p>
        <a class="db-text" style="word-wrap: break-word;" href="{{ route('ipo.index') }}">TO REVIEW</a>
    </div>
</div>
<div class="shadow-sm" style="background-color: #718c54; border-left: 3px solid white; 
  width:300px;
  max-width:300px; 
  display: inline-block;
  padding: 8px;">
    <div class="db-text d-flex align-items-center justify-content-center">
        <div>
            <p class="db-stat">{{ $countReceived }}</p>
            <a class="db-text" style="word-wrap: break-word;" href="{{ route('ipo.index') }}">RECEIVED</a>
        </div>
        <div class="ml-3 mr-3">of</div>
        <div>
            <p class="db-stat">{{ $countExpectedTotal }}</p>
            <a class="db-text" style="word-wrap: break-word;" href="{{ route('ipo.index') }}">EXPECTED</a>
        </div>
    </div>
    <div class="db-text">
        TOTAL NO. OF QAR
    </div>
</div>