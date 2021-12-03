<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Extension Service') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('faculty.extension-service.index') }}"><i class="bi bi-chevron-double-left"></i>Back</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('faculty.extension-service.store' ) }}" method="post">
                            @csrf
                            @include('extension-programs.extension-services.form', ['formFields' => $extensionServiceFields, 'colleges' => $colleges])
                            @include('extension-programs.extension-services.no-of-beneficiaries', ['value' => ''])
                            @include('extension-programs.extension-services.form2', ['formFields' => $extensionServiceFields])
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
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
        <script>
            $('#from').on('input', function(){
                var date = new Date($('#from').val());
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                // alert([day, month, year].join('-'));
                // document.getElementById("target_date").setAttribute("min", [day, month, year].join('-'));
                document.getElementById('to').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#to').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });

            $('#keywords').on('keyup', function(){
                // var value = $(this).val();
                var value = $(this).val().replace(/ /g,'');
                var words = value.split(",");
                words = words.filter(function(e){return e});
                // console.log(words);
                if(words.length < 5){
                    $("#validation-keywords").text('The number of keywords must be five (5)');
                }
                else if (words.length >= 5){
                    $("#validation-keywords").text('');
                }
                else if( words == null){
                    $("#validation-keywords").text('The number of keywords must be five (5)');
                }
            });
            
            $(function () {
                $('.funding_agency').hide();
                $('#funding_agency').removeClass('form-validation');
            });

            $('#type_of_funding').on('change', function (){
                var type = $(this).val();
                if(type == 123){
                    
                    $('.funding_agency').show();
                    $('#funding_agency').val('Polytechnic University of the Philippines');
                    $('#funding_agency').removeAttr('disabled');
                    $('#funding_agency').attr('readonly', true);
                    $('#funding_agency').addClass('form-validation');
                }
                else if(type == 124){
                    $('.funding_agency').hide();
                    $('#funding_agency').attr('disabled', true);
                    $('#funding_agency').removeClass('form-validation');
                }
                else if(type == 125){
                    $('#funding_agency').removeAttr('readonly');
                    $('#funding_agency').removeAttr('disabled');
                    $('.funding_agency').show();
                    $('#funding_agency').val('');
                    $('#funding_agency').addClass('form-validation');
                }
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
    @endpush
</x-app-layout>