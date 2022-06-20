<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Attendance in a University or College Function') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('attendance-function.index') }}"><i class="bi bi-chevron-double-left"></i>Back to Attendance in University and College Functions</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('attendance-function.store' ) }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $fields, 'value' => $values])
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
    <script>
        $('#start_date').on('change', function () {
            $('#end_date').datepicker('setDate', $('#start_date').val());
            $('#end_date').datepicker('setStartDate', $('#start_date').val());
        });

        var type = "{{ $classtype }}";
        $('#classification').on('change', function () {
            if(type == 'uni'){
                $('#classification option[value="294"]').attr("disabled","disabled");
                $('#classification option[value="295"]').attr("disabled","disabled");
            }
            else if(type == 'college')
                $('#classification option[value="293"]').attr("disabled","disabled");
        });
    </script>
    <script>
        var report_category_id = 30;
        $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');

        setTimeout(() => {
            var urlre = "{{ url('document-upload/description/30') }}";
            $.get(urlre, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        }, 2000);

    </script>
    @endpush
</x-app-layout>
