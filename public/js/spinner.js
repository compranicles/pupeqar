$('form').on('submit', function() {
    var errorElements = document.querySelectorAll(".form-control:invalid");
    $(errorElements[0]).focus();
    if ($(errorElements[0]).length != 0) {
        $('#submit').html('Save');
        $('#submit').removeAttr('disabled');
    } else {
        $('#submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
        $('#submit').attr('disabled', 'disabled');
    }
});


