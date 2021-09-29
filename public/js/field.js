
// dynamically generate input name based on label input
document.getElementById('label').onkeyup = function (){
    var name = document.getElementById('label').value;
    var slug = name.toLowerCase().replace(/ /g, '_').replace(/[^\w-]+/g, '');
    document.getElementById('field_name').value = slug;
};

// dynamically generate input name based on label input
document.getElementById('label_edit').onkeyup = function (){
    var name = document.getElementById('label_edit').value;
    var slug = name.toLowerCase().replace(/ /g, '_').replace(/[^\w-]+/g, '');
    document.getElementById('field_name_edit').value = slug;
};

// reset add Modal on close
$('#addFieldModal').on('hidden.bs.modal', function() {
    $('#field_form').get(0).reset();
});

// reset edit Modal on close
$('#editFieldModal').on('hidden.bs.modal', function() {
    $('#field_form_edit').get(0).reset();
});


// pass add data to controller using ajax
$('#field_form').on('submit', function (e) {
    var dataString = $(this).serialize();
    var routeUrl = $(this).attr('action');
    var label = document.getElementById('label').value;

    //ajax request
    $.ajax({
        type: "POST",
        url: routeUrl,
        data: dataString,
        success: function (data) {
            //hiding the opened modal
            $('#addFieldModal').modal('hide');

            // updating the html markup (i.e. adding alerts and appending the newly added field to the table)
            setTimeout(function(){
                $("#field_message").html('<div id="field_alert" class="alert alert-success"></div>');
                $("#field_alert").html("Added successfully.")
                setTimeout(function() {
                    $('#field_alert').fadeTo(500, 0).slideUp(500, function(){
                        $('#field_alert').remove(); 
                    });
                }, 4000);

                $('#hidden_fields_table').append('<tr data-id="'+data['id']+'">'+
                '<td>'+label+'</td>'+
                '<td>'+
                    '<button class="btn btn-warning edit-field" data-toggle="modal" data-target="#editFieldModal" data-form="'+data['form_id']+'" data-id="'+data['id']+'">Edit</button> '+
                    '<button class="btn btn-danger delete-field" data-target="#deleteFieldModal" data-toggle="modal" data-id="'+data['id']+'"  data-name="'+label+'">Delete</button>'+
                '</td>'+
                '</tr>');
                $('tbody.fieldsortable').sortable('refresh');
            }, 500);
        }
    });
    e.preventDefault();
});

// filling up the edit field on click
$(document).on('click', '.edit-field', function(){
    let currID =  $(this).data('id');

    let url = $('#field_form_edit').data('action');
    url = url.replace(':id', currID);
    document.getElementById('field_form_edit').action = url;

    // getting the data from controller and putting to form
    $.get('fields/info/'+ currID, function(data) {
        document.getElementById('label_edit').value = data.label;
        document.getElementById('field_name_edit').value = data.name;
        document.getElementById('size_edit').value = data.size;
        document.getElementById('field_type_edit').value = data.field_type_id;
        if(data.required === 'required'){
            $('#required_edit').prop('checked', true);
        }
        if($('#field_type_edit option:selected').text() === 'dropdown'){
            $("#dropdown_field_edit").show();
            $('#dropdown_edit').attr('required');
        }else{
            $("#dropdown_field_edit").hide();
            $("#dropdown_edit").removeAttr('required');
        }
        document.getElementById('dropdown_edit').value = data.dropdown_id;
    });
});

// submitting data to the database using ajax
$(document).on('submit', '#field_form_edit', function(e){
    var dataString = $(this).serialize();

    let url = $(this).attr('action');
    $.ajax({
        type: 'POST',
        url: url,
        data: dataString,
        success: function(data) {
            //hiding the opened modal
            $('#editFieldModal').modal('hide');

            // updating the html markup (i.e. adding alerts and appending the newly added field to the table)
            setTimeout(function(){
                $("#field_message").html('<div id="field_alert" class="alert alert-success"></div>');
                $("#field_alert").html("Updated successfully.")
                setTimeout(function() {
                    $('#field_alert').fadeTo(500, 0).slideUp(500, function(){
                        $('#field_alert').remove(); 
                    });
                }, 4000);
            }, 500);
        }
    });

    e.preventDefault();
});


// showing and putting field name and id to the modal respectively
$(document).on('click', '.delete-field', function(){
    let currID =  $(this).data('id');
    let name =  $(this).data('name');

    let url = $('#field_form_delete').data('action');
    url = url.replace(':id', currID);
    document.getElementById('field_form_delete').action = url;
    document.getElementById('field_name_delete').innerHTML = name;
    $('#button_delete_field').attr('data-id', currID);
});


// sending delete request to the database using ajax
$(document).on('submit', '#field_form_delete', function(e){
    var dataString = $(this).serialize();
    
    let url = $(this).attr('action');
    let currID =  $("#button_delete_field").attr('data-id');
    $.ajax({
        type: 'POST',
        url: url,
        data: dataString,
        success: function() {
            //hiding the opened modal
            $('#deleteFieldModal').modal('hide');

            // updating the html markup (i.e. adding alerts and appending the newly added field to the table)
            setTimeout(function(){
                $("#field_message").html('<div id="field_alert" class="alert alert-success"></div>');
                $("#field_alert").html("Deleted successfully.")
                setTimeout(function() {
                    $('#field_alert').fadeTo(500, 0).slideUp(500, function(){
                        $('#field_alert').remove(); 
                    });
                }, 4000);

                $('tbody.fieldsortable tr[data-id="'+currID+'"]').remove();
            }, 500);
        }
    });

    e.preventDefault();
});


//function to save the arrangement of fields
$(document).on('click', '#field_save_arrange', function (){
    var route = $(this).data('save');

    var allfields = [];
    var shofields = [];
    var allfieldId;
    var shofieldId;

     // getting id per row in #qar_table
     $('#hidden_fields_table').find('tbody').find('tr:not(:has(th))').each(function () {
        allfieldId = $(this).data('id');
        allfields.push({
            id : allfieldId
        });
        
    });

    // getting id per row in #nonqar_table
    $('#shown_fields_table').find('tbody').find('tr:not(:has(th))').each(function () {
        shofieldId = $(this).data('id');
        shofields.push({
            id : shofieldId
        });
    });

    // stringify
    allfields = JSON.stringify(allfields);
    shofields = JSON.stringify(shofields);

    // use ajax
    $.ajax({
        type: 'POST',
        data: {hidden: allfields, shown: shofields},
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: route,
        success: function () {
            $("#field_message").html('<div id="field_alert" class="alert alert-success alert-show"></div>');
            $("#field_alert").html("Field arrangements updated successfully.")
                setTimeout(function() {
                    $('#field_alert').fadeTo(500, 0).slideUp(500, function(){
                        $('#field_alert').remove(); 
                    });
                }, 4000);
        }
    });

    
 });

$(document).ready(function (){
    //  hide dropdown field
    $("#dropdown_field").hide();
    $("#dropdown_field_edit").hide();

    

    // show dropdown field on select
    $("#field_type").on('change', function(){
        $(this).find("option:selected").each(function(){
            var fieldtype = $(this).attr("class");
            if(fieldtype === 'dropdown'){
                $('#dropdown_field').show();
                $('#dropdown_field').attr('required');
            } else{
                $("#dropdown_field").hide();
                $("#dropdown_field").removeAttr('required');
            }
        });
    }).change();

    $("#field_type_edit").on('change', function(){
        $(this).find("option:selected").each(function(){
            var fieldtype = $(this).attr("class");
            if(fieldtype === 'dropdown'){
                $('#dropdown_field_edit').show();
                $('#dropdown_edit').attr('required');
            } else{
                $("#dropdown_field_edit").hide();
                $("#dropdown_edit").removeAttr('required');
            }
        });
    }).change();



    // connecting tables in fields tab using jquery sortable widget
    $( "tbody.fieldsortable" ).sortable({
        connectWith: ".fieldsortable",
        helper: "clone",
        cursor: "move",
        zIndex: 99999,
        items: "> tr:not(:first)",
    }); 
});