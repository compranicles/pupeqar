<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research Registration') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Colleges/Campus/Branch/Office where you commit the research</label><span style="color: red;"> *</span>
    
                                        <select name="college_id" id="college" class="form-control custom-select"  required>
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach ($colleges as $college)
                                            <option value="{{ $college->id }}">{{ $college->name }}</option>
                                            @endforeach
                                           
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Department where you commit the research</label><span style="color: red;"> *</span>

                                    <select name="department_id" id="department" class="form-control custom-select" required>
                                        <option value="" selected disabled>Choose...</option>
                                    </select>
                                </div>
                            </div>
                            
                            @include('research.form', ['formFields' => $researchFields])
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
            $('#college').on('blur', function(){
                var collegeId = $('#college').val();
                $('#department').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
                $.get('/departments/options/'+collegeId, function (data){

                    data.forEach(function (item){
                        $("#department").append(new Option(item.name, item.id));
                    });
                });
            });

            function hide_dates() {
                $('.start_date').hide();
                $('.target_date').hide();
            }

            $('#nature_of_involvement').on('change', function (){
                $('#nature_of_involvement option[value=11]').attr('selected','selected');
                // console.log(11);
                $('#nature_of_involvement').attr('disabled', true); 
            });

            $(function () {
                hide_dates();
                $('.funding_agency').hide();
                $('#researchers').val("{{ auth()->user()->first_name.' '.auth()->user()->last_name }}");
                $('#researchers').attr('readonly', true);
            });

            $('#funding_type').on('change', function (){
                var type = $(this).val();
                if(type == 23){
                    
                    $('.funding_agency').show();
                    $('#funding_agency').val('Polytechnic University of the Philippines');
                    $('#funding_agency').attr('readonly', true);
                }
                else if(type == 24){
                    $('.funding_agency').hide();
                    $('#funding_agency').attr('disabled', true);
                }
                else if(type == 25){
                    $('#funding_agency').removeAttr('readonly');
                    $('#funding_agency').removeAttr('disabled');
                    $('.funding_agency').show();
                    $('#funding_agency').val('');
                }
            });

            $('#status').on('change', function(){
                var statusId = $('#status').val();
                if (statusId == 26) {
                    hide_dates();
                    $('#start_date').removeAttr('required');
                    $('#target_date').removeAttr('required');
                    $('#start_date').attr("disabled", true);
                    $('#target_date').attr("disabled", true);
                    
                }
                else if (statusId == 27) {
                    $('.start_date').show();
                    $('.target_date').show();
                    
                    $('#start_date').attr("required", true);
                    $('#target_date').attr("required", true);
                    $('#start_date').removeAttr('disabled');
                    $('#target_date').removeAttr('disabled');
                }
            });

            $('#keywords').on('keyup', function(){
                var value = $(this).val();
                if (value != null){
                    var count = value.match(/(\w+)/g).length;
                    if(count < 5)
                        $("#validation-keywords").text('The number of keywords is still less than five (5)');
                    else{
                        $("#validation-keywords").text('');
                    }
                }
                if (value == null)
                    $("#validation-keywords").text('The number of keywords must be five (5)');
            });

            $('#start_date').on('input', function(){
                var date = new Date($('#start_date').val());
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                // alert([day, month, year].join('-'));
                // document.getElementById("target_date").setAttribute("min", [day, month, year].join('-'));
                document.getElementById('target_date').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#target_date').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });
        </script>
    @endpush
</x-app-layout>