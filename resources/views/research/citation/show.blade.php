<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research Citations') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->research_code])
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('research.citation.edit', [$research->research_code, $values['id']]) }}" class="btn btn-warning">Update</a>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>
                            </div>
                        </div>
                        <hr>
                        <fieldset id="research">
                            @include('research.form-view', ['formFields' => $researchFields, 'value' => $values,])
                        </fieldset>
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
                                                        <a href="{{ route('document.display', $document['filename']) }}" data-lightbox="gallery" data-title="{{ $document['filename'] }}">
                                                            <img src="{{ route('document.display', $document['filename']) }}" class="card-img-top img-resize"/>
                                                        </a>
                                                        
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
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

     {{-- Delete Form Modal --}}
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
                    <h5 class="text-center">Are you sure you want to delete this citation?</h5>
                    <form action="{{ route('research.citation.destroy', [$research->research_code, $values['id']]) }}" method="POST">
                        @csrf
                        @method('delete')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger mb-2 mr-2">Delete</button>
                </form>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function() {
            $('#research').prop('disabled', true);
        });
    </script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
        <script>
        $(function() {
            $('#link-to-register').show();
            $('#link-to-utilize').show();

            $('#link-to-complete').show();
            $("#link-to-publish").show();
            $("#link-to-present").show();
            $("#link-to-copyright").show();
            $("#link-to-cite").show();
        });

        if ( {{$research->status}} ==26 ){
            $('.research-tabs').remove();
        }

        else if ({{ $research->status }} == 27) {
            if ({{ $utilized }} == 0) {
                // $('#link-to-register').show();
                // $('#link-to-utilize').show();
                $('#link-to-utilize').remove();
            }
            else {
                $('.research-tabs').remove();
            }
        }

        else if ({{ $research->status }} == 28) {
            $("#link-to-cite").remove();

            if ({{ $published }} == 0) {
                $("#link-to-publish").remove();
            }

            if ({{ $presented }} == 0) {
                $("#link-to-present").remove();
            }

            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }

        else if ({{ $research->status }} == 29) {
            $("#link-to-cite").remove();

            if ({{ $published }} == 0) {
                $("#link-to-publish").remove();
            }

            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }

        else if ({{ $research->status }} == 30) {
            if ({{ $presented }} == 0) {
                $("#link-to-present").remove();
            }

            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $cited }} == 0) {
                $("#link-to-cite").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }

        else if ({{$research->status}} == 31) {
            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $cited }} == 0) {
                $("#link-to-cite").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }
    </script>
@endpush

</x-app-layout>