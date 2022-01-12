<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __($research->research_code.' > Update Research Information') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->id, 'research_status' => $research->status])
            </div>
        </div>
        {{-- Denied Details --}}
        @if ($deniedDetails = Session::get('denied'))
        <div class="alert alert-danger alert-index">
            <i class="bi bi-x-circle"></i> Denied by {{ $deniedDetails->position_name }}: {{ $deniedDetails->reason }}
        </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.update-non-lead', $research->id) }}" method="post">
                            @csrf
                            {{-- @method('put') --}}
                            <fieldset id="research">
                            @include('form', ['formFields' => $researchFields, 'value' => $values, 'colleges' => $colleges, 'collegeOfDepartment' => $collegeOfDepartment])

                            </fieldset>
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ route('research.show', $research->id) }}" class="btn btn-secondary mr-2">Cancel</a>
                                        <button type="submit" id="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
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
    </div>
    {{-- Delete doc Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">Are you sure you want to delete this document?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-danger mb-2 mr-2" id="deletedoc">Delete</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            $('#college').on('input', function(){
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
            function hide_dates() {
                $('.start_date').hide();
                $('.target_date').hide();
                $('#start_date').attr('disabled', true);
                $('#target_date').attr('disabled', true);
            }

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
            $('.document').remove();

            
            $('#title').attr('disabled', 'disabled'); 
            $('#keywords').attr('disabled', 'disabled'); 
            
            $('#researchers').attr('disabled', true);
            
            // $('#currency_select_funding_amount').empty().append('<option selected="selected" value="{{ $research->currency_funding_amount_code }}">{{ $values["currency_funding_amount"]}}</option>');
            $('#currency_select_funding_amount').attr('disabled', true);
            $('#funding_amount').attr('disabled', true);
            $('#funding_agency').attr('disabled', true);
            $('#status').empty().append('<option selected="selected" value="{{ $research->status }}">{{ $research->status_name}}</option>');
            $('#status').attr('disabled', true);
            $('#description').attr('disabled', true);

            if ({{$research->status}} == 26) {
                hide_dates();
                
            }
            else if ({{$research->status}} == 27) {
                $('.start_date').show();
                $('.target_date').show();
                $('#status').attr('disabled', true);
            }
            else if ({{ $research->status }} > 27) {
                $('#status').empty().append('<option selected="selected" value="{{ $researchStatus->id }}">{{ $researchStatus->name }}</option>');
                $('#status').attr('disabled', true);
            }
            var collegeId = $('#college').val();
            $('#department').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get('/departments/options/'+collegeId, function (data){

                data.forEach(function (item){
                    $("#department").append(new Option(item.name, item.id));
                    
                });
                document.getElementById("department").value = "{{ $values['department_id'] }}";
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
                    $('#target_date').attr("disabled", true);;
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
                document.getElementById('target_date').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#target_date').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });
        </script>
    @endpush
</x-app-layout>