<x-app-layout>
    @section('title', 'Attendance in University/College Functions |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Add Attendance in a University/College Function</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('attendance-function.index') }}"><i class="bi bi-chevron-double-left"></i>Back to Attendance in University and College Functions</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('attendance-function.store' ) }}" method="post" class="needs-validation" novalidate>
                            <div class="mt-2 mb-3">
                                <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the necessary details. No abbreviations. All inputs with the symbol (<strong style="color: red;">*</strong>) are required.
                            </div> 
                            <hr>
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
    <script src="{{ asset('js/spinner.js') }}"></script>
    <script>
         var type = "{{ $classtype }}";
            $(function() {
                if (type == 'uni') {
                    $('#classification').val(293);
                    $('#classification option[value="294"]').attr("disabled","disabled");
                    $('#classification option[value="295"]').attr("disabled","disabled");
                } else if(type == 'college') {
                    $('#classification').val(294);
                    $('#classification option[value="293"]').attr("disabled","disabled");
                    $('#classification option[value="295"]').attr("disabled","disabled");
                }
                else if(type == 'department') {
                    $('#classification').val(295);
                    $('#classification option[value="293"]').attr("disabled","disabled");
                    $('#classification option[value="294"]').attr("disabled","disabled");
                }
            });
    </script>
    <script>
        $('#start_date').on('change', function () {
            $('#end_date').datepicker('setStartDate', $('#start_date').val());
        });

        var type = "{{ $classtype }}";
        $('#classification').on('change', function () {
            if(type == 'uni'){
                $('#classification option[value="294"]').attr("disabled","disabled");
                $('#classification option[value="295"]').attr("disabled","disabled");
            } else if(type == 'college') {
                $('#classification option[value="293"]').attr("disabled","disabled");
                $('#classification option[value="295"]').attr("disabled","disabled");
            } else if(type == 'department') {
                $('#classification option[value="293"]').attr("disabled","disabled");
                $('#classification option[value="294"]').attr("disabled","disabled");
            }
        });
    </script>
    <script>
        var report_category_id = 30;
        $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');

        setTimeout(() => {
            var urlre = "{{ url('document-upload/description/33') }}";
            $.get(urlre, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        }, Math.floor(Math.random() * (2500 - 1) + 1));

    </script>
    @endpush
</x-app-layout>
