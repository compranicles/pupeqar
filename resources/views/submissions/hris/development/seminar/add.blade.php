<x-app-layout>
@section('title', 'Seminars |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @endif
                <p>
                    <a class="back_link" href="{{ route('submissions.development.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Trainings and Seminars</a>
                </p>
                {{-- Denied Details --}}
                @if ($deniedDetails = Session::get('denied'))
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-exclamation-circle"></i> Remarks: {{ $deniedDetails->reason }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('submissions.development.seminar.save', $id) }}" method="post">
                            @csrf
                            @if ($collegeOfDepartment == '')
                                @include('form', ['formFields' => $seminarFields, 'value' => $values])
                            @else
                                @include('form', ['formFields' => $seminarFields, 'value' => $values, 'colleges' => $colleges, 'collegeOfDepartment' => $collegeOfDepartment])
                            @endif
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
    <script>
        var report_category_id = 25;
        $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
        var api = '{{ url("/document-upload/description/25") }}';
		$.get(api, function (data){
            if (data != '') {
                data.forEach(function (item){
                    $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                });
            }
        });


        $(function(){
            $("input[name='document[]']").attr('required', true);
        });
    </script>
    @endpush
</x-app-layout>
