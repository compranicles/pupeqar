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
                                        <button type="submit" id="submit" class="btn btn-success">Save</button>
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
                $('#status').val(53);
                var middle = '';
                if ("{{auth()->user()->middle_name}}" != '') {
                    middle = "{{ substr(auth()->user()->middle_name,0,1).'.' }}";
                }
                var fullname = "{{ ucwords(strtolower(auth()->user()->last_name.', '.auth()->user()->first_name.' ')) }}" + middle;
                $("#collaborator")[0].selectize.addOption({value:fullname, text:fullname});
                $("#collaborator")[0].selectize.addItem(fullname);
            });
        </script>
        <script>
            $('#funding_type').on('change', function (){
                if ($(this).val() == 49) {
                    //Univ. Funded
                    $('#funding_agency').val("Polytechnic University of the Philippines");
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('required', true);
                }
                else if ($(this).val() == 50) {
                    //Self Funded
                    $('#funding_agency').val("");
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').removeAttr('required');
                }
                else if ($(this).val() == 51) { // External Funded
                    $('#funding_agency').val("");
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('required', true);
                }
            });
        </script>
        <!-- <script>
            $('#status').on('change', function(){
                $('#status option[value=54]').attr('disabled','disabled');
                $('#status option[value=55]').attr('disabled','disabled');
                if ($(this).val() == 53) {
                    $('#end_date').val("");
                    $('#end_date').attr('disabled', true);
                    $('#end_date').removeAttr('required');
                    $('#issue_date').val("");
                    $('#issue_date').attr('disabled', true);
                    $('#issue_date').removeAttr('required');
                }
            });
        </script> -->
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