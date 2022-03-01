<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Reference, Textbook, Module, Monograph, or Instructional Material') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('rtmmi.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Reference, Textbook, Module, Monographs, and Instructional Materials</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('rtmmi.store') }}" method="post">
                            @csrf
                            @include('form', ['formFields' => $referenceFields, 'colleges' => $colleges])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
                                        <button type="submit" id="submit" class="btn btn-success">Submit</button>
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
            $(document).ready(function() {
                $('.datepicker').datepicker({
                    format: 'mm/dd/yyyy'
                });
            });
        </script>
        <script>
            $('#college').on('blur', function(){
                var collegeId = $('#college').val();
                $('#department').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
                $.get('/departments/options/'+collegeId, function (data){

                    data.forEach(function (item){
                        $("#department").append(new Option(item.name, item.id));
                    });
                });
            });
        </script>
        <script>
            $('#date_started').on('input', function(){
                var date = new Date($('#date_started').val());
                if (date.getDate() <= 9) {
                        var day = "0" + date.getDate();
                }
                else {
                    var day = date.getDate();
                }

                var month = date.getMonth() + 1;
                if (month <= 9) {
                    month = "0" + month;
                }
                else {
                    month = date.getMonth() + 1;
                }
                var year = date.getFullYear();
                // alert([day, month, year].join('-'));
                // document.getElementById("target_date").setAttribute("min", [day, month, year].join('-'));
                document.getElementById('date_completed').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#date_completed').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });

            $('#date_completed').on('input', function(){
                var date = new Date($('#date_completed').val());
                if (date.getDate() <= 9) {
                        var day = "0" + date.getDate();
                }
                else {
                    var day = date.getDate();
                }

                var month = date.getMonth() + 1;
                if (month <= 9) {
                    month = "0" + month;
                }
                else {
                    month = date.getMonth() + 1;
                }
                var year = date.getFullYear();
                // alert([day, month, year].join('-'));
                // document.getElementById("target_date").setAttribute("min", [day, month, year].join('-'));
                document.getElementById('date_published').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#date_published').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });
        </script>
        <script>
            var report_category_id = 15;
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