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
        <script>
            $(function() {
                $('.funding_agency').hide();
                $('#funding_agency').removeClass('form-validation');
            });
        
            $('#funding_type').on('change', function (){
                var type = $(this).val();
                if(type == 49){
                    
                    $('.funding_agency').show();
                    $('#funding_agency').val('Polytechnic University of the Philippines');
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('readonly', true);
                    $('#funding_agency').addClass('form-validation');
                }
                else if(type == 50){
                    $('.funding_agency').hide();
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').removeClass('form-validation');
                }
                else if(type == 51){
                    $('#funding_agency').removeAttr('readonly');
                    $('#funding_agency').removeAttr('disabled');
                    $('.funding_agency').show();
                    $('#funding_agency').val('');
                    $('#funding_agency').addClass('form-validation');
                }
            });
        </script>
        <script>
            $('#status').on('change', function(){
                var statusId = $('#status').val();
                if (statusId == 26) {
                    $('#start_date').prop("required", false);
                    $('#end_date').prop("required", false);
                }
                else if (statusId == 27) {
                    $('.start_date').show();
                    $('.end_date').show();
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

            $('#end_date').on('input', function(){
                var date = new Date($('#end_date').val());
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
                document.getElementById('issue_date').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#issue_date').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });
        </script>
    @endpush
</x-app-layout>