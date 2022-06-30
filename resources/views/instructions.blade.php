<div class="alert alert-info" role="alert">
    <i class="bi bi-lightbulb-fill"></i> <strong>Reminders: </strong> <br>
    <div class="ml-3">
        &#8226; Submit your accomplishments for the Quarter {{ $currentQuarterYear->current_quarter }} on or before 
            <?php
                $deadline = strtotime( $currentQuarterYear->deadline );
                $deadline = date( 'F d, Y', $deadline);
                ?>
                <u>{{ $deadline }}</u>. <br>
        &#8226; Once you <u>submit</u> an accomplishment, you are <u>not allowed to edit</u> until the 
            quarter ends, except that it was returned to you by the Chairperson, Researcher, or Extensionist. <br>
        &#8226; Please contact them immediately if you need to edit your submitted accomplishment for them to return it to you.
    </div>
</div>