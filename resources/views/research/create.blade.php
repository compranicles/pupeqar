<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research Registration') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('research.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Research</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.store') }}" method="post" id="create_research">
                            @csrf
                            @include('form', ['formFields' => $researchFields, 'colleges' => $colleges])
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <a href="{{ route('research.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                                            <button type="submit" id="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('dist/selectize.min.js') }}"></script>
        <script>
            function hide_dates() {
                $('.start_date').hide();
                $('.target_date').hide();
                $('#start_date').removeClass('form-validation');
                $('#target_date').removeClass('form-validation');
                $('#start_date').removeAttr('required');
                $('#target_date').removeAttr('required');
                $('#start_date').attr("disabled", true);
                $('#target_date').attr("disabled", true);
            }

            $('#nature_of_involvement').on('change', function (){
                $('#nature_of_involvement option[value=12]').attr('disabled','disabled');
                $('#nature_of_involvement option[value=13]').attr('disabled','disabled');
                // $('#nature_of_involvement').attr('disabled', true); 
                $('#nature_of_involvement').removeClass('form-validation'); 
            });

            $(function () {
                hide_dates();
                $('.funding_agency').hide();
                $('#funding_agency').removeClass('form-validation');
                if ("{{ auth()->user()->middle_name }}" == '')
                {
                    $('#researchers').val("{{ auth()->user()->first_name.' '.auth()->user()->last_name }}");
                }
                else
                {
                    $('#researchers').val("{{ auth()->user()->first_name.' '.auth()->user()->middle_name.' '.auth()->user()->last_name }}");
                }
                $('#researchers').attr('readonly', true);
            });

            $('#funding_type').on('change', function (){
                var type = $(this).val();
                if(type == 23){
                    
                    $('.funding_agency').show();
                    $('#funding_agency').val('Polytechnic University of the Philippines');
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('readonly', true);
                    $('#funding_agency').addClass('form-validation');
                }
                else if(type == 24){
                    $('.funding_agency').hide();
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').removeClass('form-validation');
                }
                else if(type == 25){
                    $('#funding_agency').removeAttr('readonly');
                    $('#funding_agency').removeAttr('disabled');
                    $('.funding_agency').show();
                    $('#funding_agency').val('');
                    $('#funding_agency').addClass('form-validation');
                }
            });

            $('#status').on('change', function(){
                var statusId = $('#status').val();
                if (statusId == 26) {
                    hide_dates();
                }
                else if (statusId == 27) {
                    $('div .start_date').show();
                    $('div .target_date').show();
                    
                    $('#start_date').attr("required", true);
                    $('#target_date').attr("required", true);
                    $('#start_date').removeAttr('disabled');
                    $('#target_date').removeAttr('disabled');
                    $('#start_date').addClass('form-validation');
                    $('#target_date').addClass('form-validation');

                    $('#start_date').focus();
                }
            });

            $('#keywords').on('keyup', function(){
                // var value = $(this).val();
                var value = $(this).val().replace(/ /g,'');
                var words = value.split(",");
                words = words.filter(function(e){return e});
                // console.log(words);
                if(words.length < 5){
                    $("#validation-keywords").text('The number of keywords must be five (5)');
                }
                else if (words.length >= 5){
                    $("#validation-keywords").text('');
                }
                else if( words == null){
                    $("#validation-keywords").text('The number of keywords must be five (5)');
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

           
            // $('.form-validation').on('change', function(){
            //     if(validateForm == true){
            //         $('#submit').removeAttr('disabled');
            //     }
            //     else{
            //         $('#submit').attr('disabled', true);
            //     }
            // });
        </script>
        <script>
            var report_category_id = 1;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get('/document-upload/description/'+report_category_id, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>