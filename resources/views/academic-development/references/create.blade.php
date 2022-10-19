<x-app-layout>
@section('title', 'RTMMI |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Add RTMMI</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('rtmmi.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Reference, Textbook, Module, Monographs, and Instructional Materials</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('rtmmi.store') }}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                            <div class="mt-2 mb-3">
                                <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the necessary details. No abbreviations. All inputs with the symbol (<strong style="color: red;">*</strong>) are required.
                            </div>
                            <hr>
                            @csrf
                            @include('quarter-field')
                            @include('form', ['formFields' => $referenceFields, 'colleges' => $colleges])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
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
        <script src="{{ asset('js/spinner.js') }}"></script>
        <script>
            $(function () {
                var middle = '';
                if ("{{auth()->user()->middle_name}}" != '') {
                    middle = "{{ substr(auth()->user()->middle_name,0,1).'.' }}";
                }
                var fullname = "{{ auth()->user()->last_name.', '.auth()->user()->first_name.' ' }}" + middle;
                $("#authors_compilers")[0].selectize.addOption({value:fullname, text:fullname});
                $("#authors_compilers")[0].selectize.addItem(fullname);

            });
        </script>
         <script>
            $('#date_started').on('change', function () {
                $('#date_completed').datepicker('setDate', $('#date_started').val());
                $('#date_completed').datepicker('setStartDate', $('#date_started').val());
                $('#date_published').datepicker('setStartDate', $('#date_completed').val());
            });
        </script>
        <script>
            $('#date_completed').on('change', function () {
                $('#date_published').datepicker('setStartDate', $('#date_completed').val());
            });
        </script>
        <script>
            var report_category_id = 15;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get("{{ url('document-upload/description/15') }}", function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
