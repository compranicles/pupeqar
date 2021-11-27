<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __($research->research_code.' > Research Registration') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.code.save', $research->research_code) }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Colleges/Campus/Branch/Office where you commit the research</label>
    
                                        <select name="college_id" id="college" class="form-control custom-select"  required>
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach ($colleges as $college)
                                            <option value="{{ $college->id }}">{{ $college->name }}</option>
                                            @endforeach
                                           
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Department where you commit the research</label>

                                    <select name="department_id" id="department" class="form-control custom-select" required>
                                        <option value="" selected disabled>Choose...</option>
                                    </select>
                                </div>
                            </div>
                            
                            @include('research.form-view', ['formFields' => $researchFields, 'value' => $values])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ route('research.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                                        <button type="submit" id="submit" class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                        
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 id="textHome" style="color:maroon">Supporting Documents</h5>
                            </div>
                         </div>
                        <div class="row">
                            <div class="col-md-6">
                                <h6 style="color:maroon"><i class="far fa-file-alt mr-2"></i>Documents</h6>
                                <div class="row">
                                    @if (count($researchDocuments) > 0)
                                        @foreach ($researchDocuments as $document)
                                            @if(preg_match_all('/application\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                <div class="col-md-12 mb-3">
                                                    <div class="card bg-light border border-maroon rounded-lg">
                                                        <div class="card-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-12">
                                                                    <div class="embed-responsive embed-responsive-1by1">
                                                                        <iframe  src="{{ route('document.view', $document['filename']) }}" width="100%" height="500px"></iframe>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="col-md-4 offset-md-4">
                                            <h6 class="text-center">No Documents Attached</h6>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h6 style="color:maroon"><i class="far fa-image mr-2"></i>Images</h6>
                                <div class="row">
                                    @if(count($researchDocuments) > 0)
                                        @foreach ($researchDocuments as $document)
                                            @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                <div class="col-md-6 mb-3">
                                                    <div class="card bg-light border border-maroon rounded-lg">
                                                        <a href="{{ route('document.display', $document['filename']) }}" data-lightbox="gallery" data-title="{{ $document['filename'] }}" target="_blank">
                                                            <img src="{{ route('document.display', $document['filename']) }}" class="card-img-top img-resize"/>
                                                        </a>
                                                        
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="col-md-4 offset-md-4">
                                            <h6 class="text-center">No Images Attached</h6>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
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

            $(function() {
                hide_dates();
            });

            $('#classification').on('change', function () {
                $('#classification').attr('disabled', 'disabled'); 
            });
            $('#category').on('change', function () {
                $('#category').attr('disabled', 'disabled'); 
            });
            $('#agenda').on('change', function () {
                $('#agenda').attr('disabled', 'disabled'); 
            });
            $('#nature_of_involvement').on('change', function (){
                $('#nature_of_involvement option[value=11]').attr('disabled','disabled');
            });
            $('#research_type').on('change', function () {
                $('#research_type').attr('disabled', 'disabled'); 
            });
            $('#funding_type').on('change', function () {
                $('#funding_type').attr('disabled', 'disabled'); 
            });
            $('#currency_select').on('change', function () {
                $('#currency_select').attr('disabled', 'disabled'); 
            });
            


            $(function () {
                $('#title').attr('disabled', 'disabled'); 
                $('#keywords').attr('disabled', 'disabled'); 
                var researcher = $('#researchers').val();
                $('#researchers').val(researcher+", "+"{{ auth()->user()->first_name.' '.auth()->user()->last_name }}");
                $('#researchers').attr('disabled', true);
                $('#currency_select').empty().append('<option selected="selected" value="{{ $research->currency }}">{{ $research->currency_code}}</option>');
                $('#currency_select').attr('disabled', true);
                $('#funding_amount').attr('disabled', true);
                $('#funding_agency').attr('disabled', true);
                $('#status').empty().append('<option selected="selected" value="{{ $research->status }}">{{ $research->status_name}}</option>');
                $('#status').attr('disabled', true);
                $('#description').attr('disabled', true);
            });

            $('#status').on('change', function(){
                var statusId = $('#status').val();
                if (statusId == 26) {
                    hide_dates();
                    $('#start_date').removeAttr('required');
                    $('#target_date').removeAttr('required');
                    
                }
                else if (statusId != 27) {
                    $('.start_date').show();
                    $('.target_date').show();
                    
                    $('#start_date').attr("disabled", true);
                    $('#target_date').attr("disabled", true);
                }
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