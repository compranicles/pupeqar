jQuery(function ($){
    var i = 1;
    // add field row (add modal)
    $('#addResearcher').on('shown.bs.modal', function(event) {
        document.getElementById('addvalue').value = 'option-'+i;
    });

    $('#addOption').on('click', function(){
        i++;
        $('#dynamic_form').append('<tr id="row'+i+'"><td><input type="text" class="form-control input-parent" id="coresearcher-1" placeholder="Add co-researchers">' +
        '<div id="researcher-list">' +
       '</div>'+
        '</div></td><td><button type="button" name="remove" id="'+i+'" '+
        'class="form-control btn btn-danger btn-remove"><i class="fas fa-minus"></i></button></td></tr>');
    });

    // remove field row (add modal)
    $(document).on('click', '.btn-remove', function(){
        var button_id = $(this).attr("id");
        $("#row"+button_id).remove();
    });

    // reset add Modal on close
    $('#addResearcher').on('hidden.bs.modal', function(event) {
        for (let j = i; j >= 0; j--) {
            $('#row'+j).remove();
        }

        document.getElementById('addname').value = '';
        document.getElementById('addlabel').value = '';
        document.getElementById('addvalue').value = '';
        i = 1;
    });

    // initializing datatable
    $('#dropdown_table').DataTable({
    });
});

// starter JavaScript for disabling form submissions if there are invalid fields
(function() {
    'use strict';
    window.addEventListener('load', function() {
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.getElementsByClassName('needs-validation');
    // Loop over them and prevent submission
    var validation = Array.prototype.filter.call(forms, function(form) {
        form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
        }, false);
    });
    }, false);
})();

// auto hide alert
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);