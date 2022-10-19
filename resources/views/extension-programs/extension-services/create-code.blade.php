<x-app-layout>
    @section('title', 'Extension Programs/Projects/Activities |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Add Extension Program/ Project/Activity</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('extension-service.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Extension Services</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('extension.code.save', $value['id'] ) }}" enctype="multipart/form-data" method="post" class="needs-validation" novalidate>
                            @csrf
                            @include('quarter-field')
                            @if($notificationID != null)
                                <input type="hidden" name="notif_id" value="{{ $notificationID }}">
                            @endif
                            @include('extension-programs.extension-services.form', ['formFields' => $extensionServiceFields, 'value' => $value, 'colleges' => $colleges, 'collegeOfDepartment' => $collegeOfDepartment])
                            @include('extension-programs.extension-services.no-of-beneficiaries', ['value' => $value])
                            @include('extension-programs.extension-services.form2', ['formFields' => $extensionServiceFields, 'value' => $value, 'is_owner' => $is_owner ?? null])
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
                                            <button type="submit" id="submit" class="btn btn-success">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
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
                                            @if (!empty($extensionServiceDocuments))
                                                @foreach ($extensionServiceDocuments as $document)
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
                                                                        {{-- <div class="col-md-12">
                                                                            <button class="btn btn-danger remove-doc" data-id="doc-{{ $document['id'] }}" data-link="{{ route('extension-service.removedoc', $document['filename']) }}" data-toggle="modal" data-target="#deleteModal">Delete</button>
                                                                        </div> --}}
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
                                            @if(!empty($extensionServiceDocuments))
                                                @foreach ($extensionServiceDocuments as $document)
                                                    @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                        <div class="col-md-6 mb-3 documents-display" id="doc-{{ $document['id'] }}">
                                                            <div class="card bg-light border border-maroon rounded-lg">
                                                                <a href="{{ route('document.display', $document['filename']) }}" data-lightbox="gallery" data-title="{{ $document['filename'] }}" target="_blank">
                                                                    <img src="{{ route('document.display', $document['filename']) }}" class="card-img-top img-resize"/>
                                                                </a>
                                                                <div class="card-body">
                                                                    <table class="table table-sm my-n3 text-center">
                                                                        <tr>
                                                                            {{-- <th>
                                                                                <button class="btn btn-danger remove-doc" data-id="doc-{{ $document['id'] }}" data-link="{{ route('extension-service.removedoc', $document['filename']) }}" data-toggle="modal" data-target="#deleteModal">Delete</button>
                                                                            </th> --}}
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
                                        @endif
                                    </div>
                                </div>
                            </div>
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
        <script src="{{ asset('js/remove-document.js') }}"></script>
        <script src="{{ asset('js/spinner.js') }}"></script>
        <script>
             $('#level').attr('disabled', true);
             $('#status').attr('disabled', true);
             $('#classification').attr('disabled', true);
             $('#other_classification').attr('disabled', true);
             $('#type').attr('disabled', true);
             $('#title_of_extension_program').attr('disabled', true);
             $('#title_of_extension_project').attr('disabled', true);
             $('#title_of_extension_activity').attr('disabled', true);
             $('#funding_agency').attr('disabled', true);
             $('#type_of_funding').attr('disabled', true);
             $('#currency_select_amount_of_funding').attr('disabled', true);
             $('#amount_of_funding').attr('disabled', true);
             $('#from').attr('disabled', true);
             $('#to').attr('disabled', true);
             $('#no_of_trainees_or_beneficiaries').attr('disabled', true);
             $('#total_no_of_hours').attr('disabled', true);
             $('#classification_of_trainees_or_beneficiaries').attr('disabled', true);
             $('#other_classification_of_trainees').attr('disabled', true);
             $('#place_or_venue').attr('disabled', true);
             $('#keywords').attr('disabled', true);
             $('#qpoor').attr('disabled', true);
             $('#qfair').attr('disabled', true);
             $('#qsatisfactory').attr('disabled', true);
             $('#qverysatisfactory').attr('disabled', true);
             $('#qoutstanding').attr('disabled', true);
             $('#tpoor').attr('disabled', true);
             $('#tfair').attr('disabled', true);
             $('#tsatisfactory').attr('disabled', true);
             $('#tverysatisfactory').attr('disabled', true);
             $('#toutstanding').attr('disabled', true);
             $('#description').attr('disabled', true);
        </script>
        <script>
            var report_category_id = 12;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get("{{ url('document-upload/description/12') }}", function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
