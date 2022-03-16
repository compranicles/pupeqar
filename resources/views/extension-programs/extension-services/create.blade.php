<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Extension Service') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('extension-service.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Extension Services</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('extension-service.store' ) }}" method="post">
                            @csrf
                            @include('extension-programs.extension-services.form', ['formFields' => $extensionServiceFields, 'colleges' => $colleges])
                            @include('extension-programs.extension-services.no-of-beneficiaries', ['value' => ''])
                            @include('extension-programs.extension-services.form2', ['formFields' => $extensionServiceFields])
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
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
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
        <script>
            $(function() {
                $('#status').val(105);

                var classification = '{{ old('classification') }}';
                if (classification != 119) {
                    $('#other_classification').attr('disabled', true);
                } else {
                    $('#other_classification').removeAttr('disabled');
                }

                var classification_of_trainees_or_beneficiaries = '{{ old('classification_of_trainees_or_beneficiaries') }}';
                if (classification_of_trainees_or_beneficiaries != 130) {
                    $('#other_classification_of_trainees').attr('disabled', true);
                } else {
                    $('#other_classification_of_trainees').removeAttr('disabled');
                }
            });
        </script>
        <script>
            $('#other_classification').attr('disabled', true);
            $('#classification').on('input', function(){
                var classification_name = $("#classification option:selected").text();
                if (classification_name == "Others") {
                    $('#other_classification').removeAttr('disabled');
                    $('#other_classification').focus();
                }
                else {
                    $('#other_classification').val('');
                    $('#other_classification').attr('disabled', true);
                }
            });
        </script>
        <script>
            $('#other_classification_of_trainees').attr('disabled', true);
            $('#classification_of_trainees_or_beneficiaries').on('input', function(){
                var other_classification_of_trainees = $("#classification_of_trainees_or_beneficiaries option:selected").text();
                if (other_classification_of_trainees == "Others") {
                    $('#other_classification_of_trainees').removeAttr('disabled');
                    $('#other_classification_of_trainees').focus();
                }
                else {
                    $('#other_classification_of_trainees').val('');
                    $('#other_classification_of_trainees').attr('disabled', true);
                }
            });
        </script>
        <script>
            $('#type_of_funding').on('change', function (){
                if ($(this).val() == 123) {
                    //Univ. Funded
                    $('#funding_agency').val("Polytechnic University of the Philippines");
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('required', true);
                }
                else if ($(this).val() == 124) {
                    //Self Funded
                    $('#funding_agency').val("");
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').removeAttr('required');
                }
                else if ($(this).val() == 125) { // External Funded
                    $('#funding_agency').val("");
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('required', true);
                }
            });
        </script>
        <script>
            $('#status').on('change', function(){
                $('#status option[value=106]').attr('disabled','disabled'); //Completed
                $('#status option[value=107]').attr('disabled','disabled'); //Deferred
                if ($(this).val() == 105) { // Ongoing
                    $('#from').removeAttr('disabled');
                    $('#from').attr('required', true);
                    $('#to').val("");
                    $('#to').attr('disabled', true);
                    $('#to').removeAttr('required');
                }
            });
        </script>
        <script>
            $('#keywords').on
        </script>
        <script>
            var report_category_id = 12;
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