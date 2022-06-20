<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Extension Program/Project/Activity') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('extension-service.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Extension Services</a>
                </p>
                {{-- Denied Details --}}
                @if ($deniedDetails = Session::get('denied'))
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-exclamation-circle"></i> Remarks: {{ $deniedDetails->reason }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('extension-service.update', $value['id'] ) }}" method="post">
                            @csrf
                            @method('put')
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
                                            @if (count($extensionServiceDocuments) > 0)
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
                                            @if(count($extensionServiceDocuments) > 0)
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
        <script>
             $('#level').attr('readonly', true);
             $('#status').attr('readonly', true);
             $('#classification').attr('readonly', true);
             $('#other_classification').attr('readonly', true);
             $('#type').attr('readonly', true);
             $('#title_of_extension_program').attr('readonly', true);
             $('#title_of_extension_project').attr('readonly', true);
             $('#title_of_extension_activity').attr('readonly', true);
             $('#funding_agency').attr('readonly', true);
             $('#type_of_funding').attr('readonly', true);
             $('#currency_select_amount_of_funding').attr('disabled', true);
             $('#amount_of_funding').attr('readonly', true);
             $('#from').attr('readonly', true);
             $('#to').attr('readonly', true);
             $('#no_of_trainees_or_beneficiaries').attr('readonly', true);
             $('#total_no_of_hours').attr('readonly', true);
             $('#classification_of_trainees_or_beneficiaries').attr('readonly', true);
             $('#other_classification_of_trainees').attr('readonly', true);
             $('#place_or_venue').attr('readonly', true);
             $('#keywords').attr('readonly', true);
             $('#qpoor').attr('readonly', true);
             $('#qfair').attr('readonly', true);
             $('#qsatisfactory').attr('readonly', true);
             $('#qverysatisfactory').attr('readonly', true);
             $('#qoutstanding').attr('readonly', true);
             $('#tpoor').attr('readonly', true);
             $('#tfair').attr('readonly', true);
             $('#tsatisfactory').attr('readonly', true);
             $('#tverysatisfactory').attr('readonly', true);
             $('#toutstanding').attr('readonly', true);
             $('#description').attr('readonly', true);
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
