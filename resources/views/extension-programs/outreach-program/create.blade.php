<x-app-layout>
@section('title', 'Community Relation & Outreach Program |')

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Add Community Relation & Outreach Program</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('outreach-program.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Community Relations and Outreach Program</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('outreach-program.store' ) }}" method="post" class="needs-validation" novalidate>
                            <div class="mt-2 mb-3">
                                <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the necessary details. No abbreviations. All inputs with the symbol (<strong style="color: red;">*</strong>) are required.
                            </div> 
                            <hr>
                            @csrf
                            @include('form', ['formFields' => $outreachFields, 'colleges' => $colleges, 'colaccomp' => 1])
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
                                            <button type="submit" id="submit" class="btn btn-success">Save</button>
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
        <script src="{{ asset('js/spinner.js') }}"></script>
        <script>
            var report_category_id = 22;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get("{{ url('document-upload/description/22') }}", function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
