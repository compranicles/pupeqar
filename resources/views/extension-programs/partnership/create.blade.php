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
        <script>
            $(document).ready(function() {
                $('.datepicker').datepicker({
                    format: 'mm/dd/yyyy'
                });
            });
        </script>   
        <script>

            $('div .other_collab_nature').hide();
            var other_collab_nature = document.getElementById("other_collab_nature");
            $('#collab_nature').on('input', function(){
                var collab_nature_name = $("#collab_nature option:selected").text();
                if (collab_nature_name == "Others") {
                    $('div .other_collab_nature').show();
                    $('#other_collab_nature').focus();
                }
                else {
                    $('div .other_collab_nature').hide();
                }
            });

            $('div .other_partnership_type').hide();
            var other_partnership_type = document.getElementById("other_partnership_type");
            $('#partnership_type').on('input', function(){
                var partnership_type_name = $("#partnership_type option:selected").text();
                if (partnership_type_name == "Others") {
                    $('div .other_partnership_type').show();
                    $('#other_partnership_type').focus();
                }
                else {
                    $('div .other_partnership_type').hide();
                }
            });

            $('div .other_deliverable').hide();
            var other_deliverable = document.getElementById("other_deliverable");
            $('#deliverable').on('input', function(){
                var deliverable_name = $("#deliverable option:selected").text();
                if (deliverable_name == "Others") {
                    $('div .other_deliverable').show();
                    $('#other_deliverable').focus();
                }
                else {
                    $('div .other_deliverable').hide();
                }
            });
        </script>
        <script>
            $('#start_date').on('input', function(){
                var date = new Date($('#start_date').val());
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
                document.getElementById('end_date').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#end_date').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });

            function validateForm() {
                var isValid = true;
                $('.form-validation').each(function() {
                    if ( $(this).val() === '' )
                        isValid = false;
                });
                return isValid;
            }
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