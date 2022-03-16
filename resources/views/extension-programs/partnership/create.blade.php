<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Partnership, Linkages & Network') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('partnership.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Partnership, Linkages & Network</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('partnership.store' ) }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $partnershipFields, 'colleges' => $colleges])
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
            $('#start_date').on('change', function () {
                $('#end_date').datepicker('setDate', $('#start_date').val());
                $('#end_date').datepicker('setStartDate', $('#start_date').val());
            });
        </script>
        <script>
            $('#other_collab_nature').attr('disabled', true);
            $('#collab_nature').on('input', function(){
                var collab_nature_name = $("#collab_nature option:selected").text();
                if (collab_nature_name == "Others") {
                    $('#other_collab_nature').removeAttr('disabled');
                    $('#other_collab_nature').focus();
                }
                else {
                    $('#other_collab_nature').val('');
                    $('#other_collab_nature').attr('disabled', true);
                }
            });
        </script>
        <script>
            $('#other_partnership_type').attr('disabled', true);
            $('#partnership_type').on('input', function(){
                var partnership_type_name = $("#partnership_type option:selected").text();
                if (partnership_type_name == "Others") {
                    $('#other_partnership_type').removeAttr('disabled');
                    $('#other_partnership_type').focus();
                }
                else {
                    $('#other_partnership_type').val('');
                    $('#other_partnership_type').attr('disabled', true);
                }
            });
        </script>
        <script>
            $('#other_deliverable').attr('disabled', true);
            $('#deliverable').on('input', function(){
                var deliverable_name = $("#deliverable option:selected").text();
                if (deliverable_name == "Others") {
                    $('#other_deliverable').removeAttr('disabled');
                    $('#other_deliverable').focus();
                }
                else {
                    $('#other_deliverable').val('');
                    $('#other_deliverable').attr('disabled', true);
                }
            });
        </script>
        <script>
            var report_category_id = 13;
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