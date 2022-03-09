$('#funding_type').on('change', function (){
    var type = $(this).val();
    if(type == 23){
        $('#funding_agency').val('Polytechnic University of the Philippines');
        $('#funding_agency').attr('disabled', true);
        $('#funding_agency').addClass('form-validation');
    }
    else if(type == 24){
        $('#funding_agency').val('');
        $('#funding_agency').removeAttr('required');
        $('#funding_agency').attr('disabled', true);
        $('#funding_agency').removeClass('form-validation');
    }
    else if(type == 25){
        $('#funding_agency').val('');
        $('#funding_agency').removeAttr('disabled');
        $('#funding_agency').attr('required', true);
        $('#funding_agency').addClass('form-validation');
    }
});

$('#status').on('change', function(){
    var statusId = $('#status').val();
    if (statusId == 26) {
        $('#start_date').val("");
        $('#target_date').val("");
        $('#start_date').removeAttr('required');
        $('#target_date').removeAttr('required');
        $('#start_date').attr("disabled", true);
        $('#target_date').attr("disabled", true);
    }
    else if (statusId == 27) {
        $('#start_date').attr("required", true);
        $('#target_date').attr("required", true);
        $('#start_date').removeAttr('disabled');
        $('#target_date').removeAttr('disabled');
        $('#start_date').addClass('form-validation');
        $('#target_date').addClass('form-validation');

        $('#start_date').focus();
    }
});

$('#start_date').on('input', function(){
    var date = new Date($('#start_date').val());
    if (date.getDate() <= 9) {
            var day = "0" + date.getDate();
    }
    else {
        var day = date.getDate();
    }

    var month = date.getMonth() + 1;
    if (month <= 9) {
        month = "0" + month;
    }
    else {
        month = date.getMonth() + 1;
    }
    var year = date.getFullYear();
    // alert([day, month, year].join('-'));
    // document.getElementById("target_date").setAttribute("min", [day, month, year].join('-'));
    document.getElementById('target_date').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
    $('#target_date').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
});

function validateForm() {
    var isValid = true;
    $('.form-validation').each(function() {
        if ( $(this).val() === '' )
            isValid = false;
    });
    return isValid;
}
