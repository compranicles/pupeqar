<x-app-layout>
    @section('title', 'Syllabus |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold">Add Course Syllabus</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('syllabus.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Course Syllabi</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('syllabus.store') }}" method="post" class="needs-validation" novalidate>
                            <div class="mt-2 mb-3">
                                <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the required information with the symbol (<strong style="color: red;">*</strong>)
                            </div>
                            <hr>
                            @csrf
                            @include('form', ['formFields' => $syllabusFields, 'colleges' => $colleges, 'departments'=> $departments])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ route('syllabus.index') }}" class="btn btn-secondary mr-2">Cancel</a>
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
        var report_category_id = 16;
        $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
        $.get("{{ url('document-upload/description/16') }}", function (data){
            if (data != '') {
                data.forEach(function (item){
                    $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
