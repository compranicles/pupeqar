<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Invention, Innovation or Creative Work') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('invention-innovation-creative.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Inventions, Innovation, & Creative Works</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('invention-innovation-creative.store') }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $inventionFields, 'colleges' => $colleges])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ route('invention-innovation-creative.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                                        <button type="submit" id="submit" class="btn btn-success">Submit</button>
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
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>   
        <script>
            $(function() {
                $('#funding_agency').attr("disabled", true);
                $('#funding_agency').removeClass('form-validation');
                $('#start_date').attr("disabled", true);
                $('#end_date').attr("disabled", true);
                $('#issue_date').attr("disabled", true);
                $('#status').val(53); // Select Ongoing Status

                $("#collaborator")[0].selectize.addOption({value:"{{ auth()->user()->last_name.' '.auth()->user()->first_name.' '.substr(auth()->user()->middle_name,0,1)."." }}", text:"{{ auth()->user()->last_name.' '.auth()->user()->first_name.' '.substr(auth()->user()->middle_name,0,1)."." }}"});
                $("#collaborator")[0].selectize.addItem('{{ auth()->user()->last_name.' '.auth()->user()->first_name.' '.substr(auth()->user()->middle_name,0,1)."." }}');
            });
        
            $('#status').on('change', function (){ 
                $('#status option[value="54"]').attr("disabled", true);
                $('#status option[value="55"]').attr("disabled", true);
                if ($(this).val() == 53) {
                    $('#start_date').attr("required", true);
                    $('#start_date').removeAttr('disabled');
                    $('#end_date').removeClass('form-validation');
                    $('#end_date').removeAttr("required");
                    $('#end_date').attr("disabled", true);
                    $('#end_date').removeClass('form-validation');
                    $('#issue_date').removeAttr("required");
                    $('#issue_date').attr("disabled", true);
                    $('#issue_date').removeClass('form-validation');
                    $('#end_date').val("");
                    $('#issue_date').val("");
                }
                // else if ($(this).val() == 54) {
                //     $('.end_date').show();
                //     $('#end_date').attr("required", true);
                //     $('#end_date').removeAttr('disabled');

                //     $('.issue_date').show();
                //     $('#issue_date').attr("required", true);
                //     $('#issue_date').removeAttr('disabled');
                // }
            });

            $('#funding_type').on('change', function (){
                var type = $(this).val();
                if(type == 49){
                    $('#funding_agency').val('Polytechnic University of the Philippines');
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').addClass('form-validation');
                }
                else if(type == 50){
                    $('#funding_agency').val('');
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').removeClass('form-validation');
                }
                else if(type == 51){
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('required', true);
                    $('#funding_agency').val('');
                    $('#funding_agency').addClass('form-validation');
                }
            });
        </script>
        <!-- <script>
            $('#status').on('change', function(){
                var statusId = $('#status').val();
                if (statusId == 26) {
                    $('#start_date').prop("required", false);
                    $('#end_date').prop("required", false);
                }
                else if (statusId == 27) {
                    $('.start_date').show();
                    $('.end_date').show();
                }
            });
        </script> -->
        <script>
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
                document.getElementById('end_date').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#end_date').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });

            $('#end_date').on('input', function(){
                var date = new Date($('#end_date').val());
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
                document.getElementById('issue_date').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#issue_date').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });
        </script>
        <script>
            var report_category_id = 8;
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