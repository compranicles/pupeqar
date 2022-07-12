<x-app-layout>
    @section('title', 'Research & Book Chapter |')
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
        <h3 class="font-weight-bold mr-2">Edit {{ $research->research_code }}</h3>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('research.update', $research->id) }}" method="post" class="needs-validation" novalidate>
                            @csrf
                            @method('put')
                            @include('form', ['formFields' => $researchFields, 'value' => $values, 'colleges' => $colleges, 'collegeOfDepartment' => $collegeOfDepartment])

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
        <div class="row mt-3" id="documentsSection">
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
                                                <div class="col-md-12 mb-3 documents-display" id="doc-{{ $document['id'] }}">
                                                    <div class="card bg-light border border-maroon rounded-lg">
                                                        <div class="card-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-12">
                                                                    <div class="embed-responsive embed-responsive-1by1">
                                                                        <iframe  src="{{ route('document.view', $document['filename']) }}" width="100%" height="500px"></iframe>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <button class="btn btn-danger remove-doc" data-id="doc-{{ $document['id'] }}" data-link="{{ route('research.removedoc', $document['filename']) }}" data-toggle="modal" data-target="#deleteModal">Delete</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <div class="col-md-4 offset-md-4 docEmptyMessage" style="display: none;">
                                            <h6 class="text-center">No Documents Attached</h6>
                                        </div>
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
                                                <div class="col-md-6 mb-3 documents-display" id="doc-{{ $document['id'] }}">
                                                    <div class="card bg-light border border-maroon rounded-lg">
                                                        <a href="{{ route('document.display', $document['filename']) }}" data-lightbox="gallery" data-title="{{ $document['filename'] }}" target="_blank">
                                                            <img src="{{ route('document.display', $document['filename']) }}" class="card-img-top img-resize"/>
                                                        </a>
                                                        <div class="card-body">
                                                            <table class="table table-sm my-n3 text-center">
                                                                <tr>
                                                                    <th>
                                                                        <button class="btn btn-danger remove-doc" data-id="doc-{{ $document['id'] }}" data-link="{{ route('research.removedoc', $document['filename']) }}" data-toggle="modal" data-target="#deleteModal">Delete</button>
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                        <div class="col-md-4 offset-md-4 docEmptyMessage" style="display: none;">
                                            <h6 class="text-center">No Documents Attached</h6>
                                        </div>
                                    @else
                                        <div class="col-md-4 offset-md-4">
                                            <h6 class="text-center">No Documents Attached</h6>
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
        <script src="{{ asset('js/remove-document.js') }}"></script>
        <script src="{{ asset('js/spinner.js') }}"></script>
        <script>
                $(function() {
                    $("#status").prop('disable', true);
                });
                if ({{ $research->funding_type }} == 23) {
                    //Univ. Funded
                    $('#funding_agency').removeAttr('readonly');
                    $('#funding_agency').attr('required', true);
                }
                else if ({{ $research->funding_type }} == 24) {
                    //Self Funded
                    $('#funding_agency').attr('readonly', true);
                    $('#funding_agency').removeAttr('required');
                }
                else { // External Funded
                    $('#funding_agency').removeAttr('readonly');
                    $('#funding_agency').attr('required', true);
                }

                /* STATUS On page load */
                if ({{ $research->status }} == 26) {
                    $('#start_date').val("");
                    $('#start_date').attr('disabled', true);
                    $('#start_date').removeAttr('required');
                    $('#target_date').val("");
                    $('#target_date').attr('disabled', true);
                    $('#target_date').removeAttr('required');
                }
                else if ({{ $research->status }} == 27) {
                    $('#start_date').removeAttr('disabled');
                    $('#start_date').attr('required', true);
                    $('#target_date').removeAttr('disabled');
                    $('#target_date').attr('required', true);
                }
        </script>
        <script>
            $('#funding_type').on('change', function (){
                if ($(this).val() == 23) {
                    //Univ. Funded
                    $('#funding_agency').val("Polytechnic University of the Philippines");
                    $('#funding_agency').removeAttr('readonly');
                    $('#funding_agency').attr('required', true);
                }
                else if ($(this).val() == 24) {
                    //Self Funded
                    $('#funding_agency').val("");
                    $('#funding_agency').attr('readonly', true);
                    $('#funding_agency').removeAttr('required');
                }
                else if ($(this).val() == 25) { // External Funded
                    $('#funding_agency').val("");
                    $('#funding_agency').removeAttr('readonly');
                    $('#funding_agency').attr('required', true);
                }
            });
        </script>
        <script>
            $('#status').on('change', function(){
                $('#status option[value=28]').attr('disabled','disabled');
                $('#status option[value=29]').attr('disabled','disabled');
                $('#status option[value=30]').attr('disabled','disabled');
                $('#status option[value=31]').attr('disabled','disabled');
                if ($(this).val() == 26) {
                    $('#start_date').val("");
                    $('#start_date').attr('disabled', true);
                    $('#start_date').removeAttr('required');
                    $('#target_date').val("");
                    $('#target_date').attr('disabled', true);
                    $('#target_date').removeAttr('required');

                }
                else if ($(this).val() == 27) {
                    $('#start_date').removeAttr('disabled');
                    $('#start_date').attr('required', true);
                    $('#target_date').removeAttr('disabled');
                    $('#target_date').attr('required', true);
                    $('#start_date').focus();
                }
            });
        </script>
        <script>
            $('#start_date').on('change', function () {
                $('#target_date').datepicker('setDate', $('#start_date').val());
                $('#target_date').datepicker('setStartDate', $('#start_date').val());
            });
        </script>
        <script>
            $('#nature_of_involvement').on('change', function (){
                $('#nature_of_involvement option[value=12]').attr('disabled','disabled');
                $('#nature_of_involvement option[value=13]').attr('disabled','disabled');
            });
        </script>
        <script>
             var report_category_id = 1;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            var apinb = '{{ url("/document-upload/description/1") }}';
            setTimeout(function (){
            $.get(apinb, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            }); }, 2000);
        </script>
    @endpush
</x-app-layout>
