<x-app-layout>
    @section('title', 'Partnership, Linkages & Network |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Edit Partnership, Linkages & Network</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('partnership.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Partnership, Linkages & Network</a>
                </div>
                {{-- Denied Details --}}
                @if ($deniedDetails = Session::get('denied'))
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-exclamation-circle"></i> Remarks: {{ $deniedDetails->reason }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('partnership.update', $partnership->id) }}" method="post" class="needs-validation" novalidate>
                            @csrf
                            @method('put')
                            @include('form', ['formFields' => $partnershipFields, 'value' => $values, 'colleges' => $colleges])
                            <div class="col-md-12">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
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
                                    @if (count($documents) > 0)
                                        @foreach ($documents as $document)
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
                                                                    <button class="btn btn-danger remove-doc" data-id="doc-{{ $document['id'] }}" data-link="{{ route('partnership.removedoc', $document['filename']) }}" data-toggle="modal" data-target="#deleteModal">Delete</button>
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
                                    @if(count($documents) > 0)
                                        @foreach ($documents as $document)
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
                                                                        <button class="btn btn-danger remove-doc" data-id="doc-{{ $document['id'] }}" data-link="{{ route('partnership.removedoc', $document['filename']) }}" data-toggle="modal" data-target="#deleteModal">Delete</button>
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
            $('#start_date').on('change', function () {
                $('#end_date').datepicker('setStartDate', $('#start_date').val());
            });
        </script>
        <script>
            $("#collab_nature").selectize({
                maxItems: 5,
                delimiter: ",",
                persist: true,
                create: function (input) {
                    return {
                    value: input,
                    text: input,
                    };
                },
            });
        </script>
        <script>
            $("#partnership_type").selectize({
                maxItems: 5,
                delimiter: ",",
                persist: true,
                create: function (input) {
                    return {
                    value: input,
                    text: input,
                    };
                },
            });
        </script>
        <script>
            $("#deliverable").selectize({
                maxItems: 5,
                delimiter: ",",
                persist: true,
                create: function (input) {
                    return {
                    value: input,
                    text: input,
                    };
                },
            });
        </script>
        <script>
            var report_category_id = 13;
            $('#description').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get("{{ url('document-upload/description/13') }}", function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#description")[0].selectize.addOption({value:item.name, text:item.name});
                    });
                }
            });
        </script>
        <script>
            var dropdown_id = 30;
            setTimeout(function (){
                $('#collab_nature').empty().append('<option selected="selected" disabled="disabled" value=""></option>');
                $.get("{{ url('dropdowns/options/30') }}", function (data){
                    if (data != '') {
                        data.forEach(function (item){
                            $("#collab_nature")[0].selectize.addOption({value:item.name, text:item.name});
                        });
                    }
                });
            }, Math.floor(Math.random() * (2500 - 1) + 1));
        </script>
        <script>
            var dropdown_id = 31;
            setTimeout(function (){
                $('#partnership_type').empty().append('<option selected="selected" disabled="disabled" value=""></option>');
                $.get("{{ url('dropdowns/options/31') }}", function (data){
                    if (data != '') {
                        data.forEach(function (item){
                            $("#partnership_type")[0].selectize.addOption({value:item.name, text:item.name});
                        });
                    }
                });
            }, Math.floor(Math.random() * (2500 - 1) + 1));
        </script>
         <script>
            var dropdown_id = 32;
            setTimeout(function (){
                $('#deliverable').empty().append('<option selected="selected" disabled="disabled" value=""></option>');
                $.get("{{ url('dropdowns/options/32') }}", function (data){
                    if (data != '') {
                        data.forEach(function (item){
                            $("#deliverable")[0].selectize.addOption({value:item.name, text:item.name});
                        });
                    }
                });
            }, Math.floor(Math.random() * (2500 - 1) + 1));
        </script>
    @endpush
</x-app-layout>
