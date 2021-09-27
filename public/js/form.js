// dynamically generate slug based on name input
document.getElementById('name').onkeyup = function (event){
    var name = document.getElementById('name').value;
    var slug = name.toLowerCase().replace(/ /g, '-').replace(/[^\w-]+/g, '');
    document.getElementById('form_name').value = slug;
};

// reset add Modal on close
$('#addModal').on('hidden.bs.modal', function(event) {
    document.getElementById('name').value = '';
    document.getElementById('form_name').value = '';
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

// saving data and alert dynamically
$( "#form_form" ).on( "submit", function(e) {

    var dataString = $(this).serialize();
    var routeUrl = $(this).data('route');

    $.ajax({
        type: "POST",
        url: routeUrl,
        data: dataString,
        success: function () {
        $("#form_message").html('<div id="message" class="alert alert-success alert-show"></div>');
        $("#message").html("Updated successfully.")
            setTimeout(function() {
                $('.alert-show').fadeTo(500, 0).slideUp(500, function(){
                    $('.alert-show').remove(); 
                });
            }, 4000);
        }
    });

    e.preventDefault();
});

// putting id to form action (delete Modal)
$('.deletebutton').click(function (e){
    let currID =  $(this).data('id');
    let url = document.getElementById('form_delete').action;
    let formName = $(this).data('formname');
    url = url.replace(':id', currID);
    document.getElementById('form_delete').action = url;
    document.getElementById('formdisplay').innerHTML = formName;
});

// auto hide alert
window.setTimeout(function() {
    $(".alert-index").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove(); 
    });
}, 4000);

$(document).ready( function () {

    //initializing data table for form_table
    $('#form_table').DataTable({
    });

    // connecting tables in arrange tab using jquery sortable widget
    $( "tbody.connectedSortable" ).sortable({
        connectWith: ".connectedSortable",
        helper: "clone",
        cursor: "move",
        zIndex: 99999,
        items: "> tr:not(:first)",
    }); 

    // saving the new arrangements using click
    $('#save_arrange').click(function () {
        var route = $(this).data('save');
        var dataqar = [];
        var rowIdqar;
        var datanonqar = [];
        var rowIdnonqar;
        
        // getting id per row in #qar_table
        $('#qar_table').find('tbody').find('tr:not(:has(th))').each(function () {
            rowIdqar = $(this).data('id');
            dataqar.push({
                form_id : rowIdqar
            });
        });

        // getting id per row in #nonqar_table
        $('#nonqar_table').find('tbody').find('tr:not(:has(th))').each(function () {
            rowIdnonqar = $(this).data('id');
            datanonqar.push({
                form_id : rowIdnonqar
            });
        });
        

        //submit to table
        submit(dataqar, datanonqar, route);
     });

    //submit to forms.arrange using
     function submit(qar, nonqar, route){
        var data1 = JSON.stringify(qar);
        var data2 = JSON.stringify(nonqar);
        $.ajax({
            type: 'POST',
            data: {qar: data1, nonqar: data2},
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: route,
            success: function () {
                $("#alert_message").html('<div id="message" class="alert alert-success alert-show"></div>');
                $("#message").html("Form arrangements updated successfully.")
                    setTimeout(function() {
                        $('.alert-show').fadeTo(500, 0).slideUp(500, function(){
                            $('.alert-show').remove(); 
                        });
                    }, 4000);
            }
        });
     }
} );
