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
            <i class="bi bi-x-circle"></i> Remarks: {{ $deniedDetails->reason }}
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
        <script src="{{ asset('dist/selectize.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>   
        <script src="{{ asset('js/views/research.js') }}"></script>   
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
            $('#classification').on('change', function () {
                $('#classification').attr('disabled', true); 
            });
            $('#category').on('change', function () {
                $('#category').attr('disabled', true); 
            });
            $('#agenda').on('change', function () {
                $('#agenda').attr('disabled', true); 
            });
            $('#nature_of_involvement').on('change', function (){
                $('#nature_of_involvement option[value=11]').attr('disabled',true);
                $('#nature_of_involvement option[value=224]').attr('disabled',true);
            });
            $('#research_type').on('change', function () {
                $('#research_type').attr('disabled', true); 
            });
            $('#funding_type').on('change', function () {
                $('#funding_type').attr('disabled', true); 
            });
            $('#currency_select').on('change', function () {
                $('#currency_select').attr('disabled', true); 
            });
            $('.document').remove();

            
            $('#title').attr('disabled', true); 
            $('#keywords').attr('disabled', true); 
            
            $('#researchers').attr('disabled', true);
            
            // $('#currency_select_funding_amount').empty().append('<option selected="selected" value="{{ $research->currency_funding_amount_code }}">{{ $values["currency_funding_amount"]}}</option>');
            $('#currency_select_funding_amount').attr('disabled', true);
            $('#funding_amount').attr('disabled', true);
            $('#funding_agency').attr('disabled', true);
            $('#status').empty().append('<option selected="selected" value="{{ $research->status }}">{{ $research->status_name}}</option>');
            $('#status').attr('disabled', true);
            $('#description').attr('disabled', true);

            if ({{$research->status}} == 26) {
                $('#start_date').attr('disabled', true);
                $('#target_date').attr('disabled', true);
                
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
        </script>
        <script>
            var report_category_id = 1;
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