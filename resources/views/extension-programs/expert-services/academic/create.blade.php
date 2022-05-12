<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Expert Service Rendered in Academic Journals/Books/Publication/Newsletter/Creative Works') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('expert-service-in-academic.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Expert Services in Academic Journals, Books, Publication, Newsletter, & Creative Works</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('expert-service-in-academic.store' ) }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $expertServiceAcademicFields, 'colleges' => $colleges])
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
            $('#from').on('change', function () {
                $('#to').datepicker('setDate', $('#from').val());
                $('#to').datepicker('setStartDate', $('#from').val());
            });
        </script>
        {{-- <script>
            $("#nature").selectize({
                maxItems: 5,
                delimiter: ",",
                persist: true,
                create: function (input) {
                    return {
                    value: input,
                    text: input,
                    };
                },
            });
        </script> --}}
        <script>
            var report_category_id = 11;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get('/document-upload/description/'+report_category_id, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
        {{-- <script>
            var dropdown_id = 20;
            $('#nature').empty().append('<option selected="selected" disabled="disabled" value=""></option>');
            $.get('/dropdowns/options/'+dropdown_id, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#nature")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script> --}}
    @endpush
</x-app-layout>