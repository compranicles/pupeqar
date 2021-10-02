$(document).ready(function (){
    var i = 1;
    var count = 1;
    var option = 1;

    // add field row (add modal)

    $('#addModal').on('shown.bs.modal', function(event) {
        document.getElementById('addvalue').value = 'option-'+i;
    });

    $('#addOption').click(function(){
        i++;
        $('#dynamic_form').append('<tr id="row'+i+'"><td><input class="form-control" '+
            'type="text" name="label[]" required><div class="invalid-feedback">This is required. '+
            '</div></td><td><input class="form-control" type="text" value="option-'+i+'" name="value[]" readonly required>'+
            '<div class="invalid-feedback">This is required. </div></td><td><button type="button" name="remove" id="'+i+'" '+
            'class="form-control btn btn-danger btn-remove"><i class="fas fa-minus"></i></button></td></tr>');
    });

    // remove field row (add modal)
    $(document).on('click', '.btn-remove', function(){
        var button_id = $(this).attr("id");
        $("#row"+button_id).remove();
    });

    // reset add Modal on close
    $('#addModal').on('hidden.bs.modal', function(event) {
        for (let j = i; j >= 0; j--) {
            $('#row'+j).remove();
        }

        document.getElementById('addname').value = '';
        document.getElementById('addlabel').value = '';
        document.getElementById('addvalue').value = '';
        i = 1;
    });

    // adding values to edit modal when edit button is clicked
    $(".edit-row").click(function (e){
        let currID =  $(this).data('id');

        //putting id to route of form action
        let url = document.getElementById('form_edit').action;
        url = url.replace(':id', currID);
        document.getElementById('form_edit').action = url;

        $.get('dropdowns/modal/'+currID, function (data){
            document.getElementById('editname').value = data.name;
        });

        $.get('dropdowns/options/'+currID, function (data){
            data.forEach(function (item){
                if(count == 1){
                    $('#dynamic_edit_form').append('<tr id="rowEdit'+count+'"><td><input class="form-control" '+
                        'type="text" name="label[]" value="'+item.label+'" required><div class="invalid-feedback">This is required. '+
                        '</div></td><td><input class="form-control" type="text" name="value[]" id="value-'+count+'" value="'+item.value+'" readonly required>'+
                        '<div class="invalid-feedback">This is required. </div></td><td><button type="button"'+
                        'name="add" class="form-control btn btn-success btn-add-edit" ><i class="fas fa-plus"></i></button></td></tr>');
                    count++;
                }
                else{
                    $('#dynamic_edit_form').append('<tr id="rowEdit'+count+'"><td><input class="form-control" '+
                    'type="text" name="label[]" value="'+item.label+'" required><div class="invalid-feedback">This is required. '+
                    '</div></td><td><input class="form-control" type="text" id="value-'+count+'" name="value[]" value="'+item.value+'" readonly required>'+
                    '<div class="invalid-feedback">This is required. </div></td><td><button type="button" name="remove" id="'+count+'" '+
                    'class="form-control btn btn-danger btn-remove-edit"><i class="fas fa-minus"></i></button></td></tr>');
                    count++;
                }
            });
            // get the last option count as a basis for continuous option numbering
            count--;
            option = parseInt(document.getElementById("value-"+count).value.replace('option-', ''));
            option++;
            count++;
        });
    });

    // add field row (edit modal)
    $(document).on('click', '.btn-add-edit', function(){
        $('#dynamic_edit_form').append('<tr id="rowEdit'+option+'"><td><input class="form-control" '+
            'type="text" name="label[]" required><div class="invalid-feedback">This is required. '+
            '</div></td><td><input class="form-control" type="text" id="value-'+count+'" value="option-'+option+'" name="value[]" readonly required>'+
            '<div class="invalid-feedback">This is required. </div></td><td><button type="button" name="remove" id="'+option+'" '+
            'class="form-control btn btn-danger btn-remove-edit"><i class="fas fa-minus"></i></button></td></tr>');
        option++;
        count++;
    });

    // remove field row (edit modal)
    $(document).on('click', '.btn-remove-edit', function(){
        var button_id = $(this).attr("id");
        $("#rowEdit"+button_id).remove();
    });

    // reset add Modal on close
    $('#editModal').on('hidden.bs.modal', function(event) {
        for (let j = count; j >= 0; j--) {
            $('#rowEdit'+j).remove();
        }
        document.getElementById('editname').value = '';
        count = 1;
    });


    // putting id to form action (delete Modal)
    $('.delete-row').click(function (e){
        let currID =  $(this).data('id');
        let url = document.getElementById('form_delete').action;
        let dropdownName = $(this).data('dropdownname');
        url = url.replace(':id', currID);
        document.getElementById('form_delete').action = url;
        document.getElementById('dropdown_name').innerHTML = dropdownName;
    })

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