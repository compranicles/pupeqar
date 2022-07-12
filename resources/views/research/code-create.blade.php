<x-app-layout>
    @section('title', 'Research & Book Chapter |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Register {{ $research->research_code }} Research/Book Chapter</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('research.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Research</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.code.save', $research->id) }}" method="post" class="needs-validation" novalidate>
                            <div class="mt-2 mb-3">
                                <i class="bi bi-pencil-square mr-1"></i><strong>Instructions: </strong> Please fill in the required information with the symbol (<strong style="color: red;">*</strong>)
                            </div> 
                            <hr>
                            @csrf
                            @if($notificationID != null)
                                <input type="hidden" name="notif_id" value="{{ $notificationID }}">
                            @endif
                            @include('form', ['formFields' => $researchFields, 'value' => $values, ])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ route('research.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                                        <button type="submit" id="submit" class="btn btn-success">Save</button>
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
        <script src="{{ asset('dist/selectize.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
        <script src="{{ asset('js/spinner.js') }}"></script>
        <script>
            $(function () {
                var middle = '';
                if ("{{auth()->user()->middle_name}}" != '') {
                    middle = "{{ substr(auth()->user()->middle_name,0,1).'.' }}";
                }
                var fullname = "{{ ucwords(strtolower(auth()->user()->last_name.', '.auth()->user()->first_name.' ')) }}" + middle;
                $("#researchers")[0].selectize.addOption({value:fullname, text:fullname});
                $("#researchers")[0].selectize.addItem(fullname);
                $("#researchers")[0].selectize.lock();
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
                $('#nature_of_involvement option[value=11]').attr('disabled','disabled');
                $('#nature_of_involvement option[value=224]').attr('disabled','disabled');
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
            // $('#researchers').attr('readonly', true);
            $('#currency_select_funding_amount').attr('disabled', true);
            $('#funding_amount').attr('disabled', true);
            $('#funding_agency').attr('disabled', true);
            $('#status').attr('disabled', true);
            $('#description').attr('disabled', true);
            $('#start_date').attr('disabled', true);
            $('#target_date').attr('disabled', true);
        </script>
    @endpush
</x-app-layout>