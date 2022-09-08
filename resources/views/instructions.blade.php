<div class="alert alert-info" role="alert">
    <i class="bi bi-lightbulb-fill"></i> <strong>Reminders: </strong> <br>
    <div class="ml-3">
        &#8226; Submit your accomplishments for the Quarter {{ $currentQuarterYear->current_quarter }} on or before 
            <?php
                $deadline = strtotime( $currentQuarterYear->deadline );
                $deadline = date( 'F d, Y', $deadline);
                ?>
                <strong>{{ $deadline }}</strong>. <br>
        &#8226; Once you <strong>submit</strong> an accomplishment, you are <strong>not allowed to edit</strong> until the 
            quarter ends, except that it was returned to you by the Chairperson, Researcher, or Extensionist.
            Please contact them immediately if you need to edit your submitted accomplishment for them to return it to you.
        <!-- &#8226; You may <a class="text-primary" style="text-decoration:underline" href="{{ route('offices.create') }}" onclick="{{ session(['url' => url()->current()]) }}">add college/branch/campus/offices where you are reporting.</a> -->
    </div>
</div>