$(document).ready(function() {
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'mm/dd/yyyy',
        immediateUpdates: true,
        todayBtn: "linked",
        todayHighlight: true,    
    });

    $('.datepicker').datepicker('startDate', "<?php if (old($fieldInfo->name) == '') { if ($value != '') { echo date('m/d/Y', strtotime($value)); } else { echo ''; }} else { echo date('m/d/Y', strtotime(old($fieldInfo->name))); } ?>");
});
 