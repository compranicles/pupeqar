<div class="alert alert-info" role="alert">
    <i class="bi bi-lightbulb-fill"></i> <strong>Reminders: </strong> <br>
    <div class="ml-3">
        &#8226; Once you <strong>submit</strong> an accomplishment, you are <strong>not allowed to edit</strong> until the 
            quarter ends, except that it was returned to you by the Chairperson, Researcher, or Extensionist.
            You may request them to return your accomplishment if revision is necessary. <br>
        &#8226; Submit your accomplishments for the <strong>Quarter {{ $currentQuarterYear->current_quarter }}</strong> on or before 
            <?php
                $deadline = strtotime( $currentQuarterYear->deadline );
                $deadline = date( 'F d, Y', $deadline);
                ?>
                <strong>{{ $deadline }}</strong>.
        <!-- &#8226; You may <a class="text-primary" style="text-decoration:underline" href="{{ route('offices.create') }}" onclick="{{ session(['url' => url()->current()]) }}">add college/branch/campus/offices where you are reporting.</a> -->
    </div>
</div>