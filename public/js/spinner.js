$('form').on('submit', function() {
    $('#submit').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');
    $('#submit').attr('disabled', 'disabled');
});