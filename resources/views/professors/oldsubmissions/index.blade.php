<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __($event->name) }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-lg-4">
                <div class="card mb-3">
                    <div class="card-body">
                        @if ($message = Session::get('success_event'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-11">
                                <div>
                                    <a href="{{ route('professor.events.index') }}" class="btn btn-secondary mb-2"><i class="fas fa-arrow-left mr-2"></i> Back</a>
                                </div>
                                <table class="table table-responsive table-sm mw-auto mb-3" width="100%">
                                    <tbody>
                                        <tr>
                                            <th width="35%">Event Name</th>
                                            <td width="65%">{{ $event->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Event Type</th>
                                            <td>
                                                @foreach ($event_types as $event_type)
                                                    {{ (($event->event_type_id == $event_type->id) ?  $event_type->name : '') }}
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td>{{ $event->description }}</td>
                                        </tr>
                                        <tr>
                                            <th>Organizer</th>
                                            <td>{{ $event->organizer }}</td>
                                        </tr>
                                        <tr>
                                            <th>Sponsor</th>
                                            <td>{{ $event->sponsor }}</td>
                                        </tr>
                                        <tr>
                                            <th>Event Duration</th>
                                            <td>{{ date("F j, Y", strtotime($event->start_date)) }} - {{ date("F j, Y" , strtotime($event->end_date)) }}</td>
                                        </tr>
                                        <tr>
                                            <th>Event Venue</th>
                                            <td>{{ $event->location }}</td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td>
                                                @if ($event->status == 0)
                                                    <a class="btn btn-warning btn-sm btn-disabled text-dark">Pending</a>
                                                @elseif($event->status == 1)
                                                    <a class="btn btn-sm btn-success btn-disabled">Accepted</a>
                                                @elseif($event->status == 2)
                                                    <a class="btn btn-sm btn-danger btn-disabled">Rejected</a>
                                                @elseif($event->status == 3)
                                                    <a class="btn btn-sm btn-dark btn-disabled">Closed</a>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Created By</th>
                                            <td>{{ $created_by->first_name." ".$created_by->last_name }}</td>
                                        </tr>
                                    </tbody>
                                </table>        
                            </div>
                            @if (\Auth::id() == $event->created_by)
                                <div class="col-1 ml-n3 mt-n2">
                                    <div class="dropdown">
                                        <button class="btn" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <form action="{{  route('professor.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <a href="{{ route('professor.events.edit', $event->id) }}"  class="dropdown-item"><i class="far fa-edit"></i> Edit</a>
                                                <div class="dropdown-divider"></div>
                                                <button type="submit" class="dropdown-item text-danger"><i class="far fa-trash-alt"></i> Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <h5 id="textHome" style="color:maroon">Add Submission</h5>
                            </div>
                        </div>
                        @if ($message = Session::get('success_submission'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    {{-- <div class="col-4">
                                        <ul class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                            <li class="nav-item pill-1">
                                                <a class="nav-link active" class="text-dark" id="v-pills-text-tab" data-toggle="pill" href="#v-pills-text" role="tab" aria-controls="v-pills-text" aria-selected="true"><i class="fas fa-align-left mr-2"></i>  Text</a>
                                            </li>
                                            <li class="nav-item pill-2">
                                                <a class="nav-link active" id="v-pills-document-tab" data-toggle="pill" href="#v-pills-document" role="tab" aria-controls="v-pills-document" aria-selected="false"><i class="far fa-file-alt mr-2"></i>  Document</a>
                                            </li>
                                            <li class="nav-item pill-3">
                                                <a class="nav-link" id="v-pills-image-tab" data-toggle="pill" href="#v-pills-image" role="tab" aria-controls="v-pills-image" aria-selected="false"><i class="far fa-image mr-2"></i>  Image</a>
                                            </li>
                                        </ul>
                                    </div> --}}
                                    {{-- col-8 the original  --}}
                                    <div class="col-12">  
                                        {{-- <div class="tab-content" id="v-pills-tabContent"> --}}
                                            {{-- <div class="tab-pane fade show active" id="v-pills-text" role="tabpanel" aria-labelledby="v-pills-text-tab">
                                                <form method="POST" action="">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <textarea name="message" id="message" :value="old('message')" cols="30" rows="10" class="{{ $errors->has('message') ? 'is-invalid' : '' }} form-control" required autofocus autocomplete="message"></textarea>
                                                                <x-jet-input-error for="message"></x-jet-input-error>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-0">
                                                        <div class="d-flex justify-content-end align-items-baseline">
                                                            <x-jet-button>
                                                                {{ __('submit') }}
                                                            </x-jet-button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div> --}}
                                            <div class="tab-pane fade show active" id="v-pills-document" role="tabpanel" aria-labelledby="v-pills-document-tab">
                                                <form action="{{ route('professor.events.submissions.store', $event->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <label>Upload Images or Documents</label>
                                                                <input type="file" 
                                                                    class="filepond mb-n1"
                                                                    name="document[]"
                                                                    multiple
                                                                    data-max-file-size="5MB"
                                                                    data-max-files="10"/>
                                                                    @error('document')
                                                                        <small class="text-danger" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </small>
                                                                    @enderror
                                                                <p class="mt-1"><small>Maximum size per file: 5MB. Maximum number of files: 15.</small></p>
                                                                <p class="mt-n4"><small>Accepts PDF, JPEG, and PNG file formats.</small></p>
                                                                <div class="mt-n3">
                                                                    <div class="d-flex justify-content-end align-items-baseline">
                                                                        <x-jet-button>
                                                                            {{ __('submit') }}
                                                                        </x-jet-button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                            {{-- <div class="tab-pane fade" id="v-pills-image" role="tabpanel" aria-labelledby="v-pills-image-tab">
                                                <form method="POST" action="{{ route('professor.events.submissions.store', $event->id) }}"  enctype="multipart/form-data">
                                                    @csrf
                                                    <p><small>Maximum size per file: 5MB. Maximum number of files: 10.</small></p>
                                                    <div class="row mb-3">
                                                        <div class="col-12">
                                                            <div class="form-group">
                                                                <input type="file" 
                                                                    class="filepond"
                                                                    id="image"
                                                                    name="image[]"
                                                                    multiple
                                                                    data-max-file-size="5MB"
                                                                    data-max-files="10" required/>
                                                                @error('image')
                                                                    <small class="text-danger" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </small>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mb-3">
                                                        <div class="d-flex justify-content-end align-items-baseline">
                                                            <x-jet-button>
                                                                {{ __('submit') }}
                                                            </x-jet-button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>                                  
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h5 id="textHome" style="color:maroon">Submissions</h5>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                    <li class="nav-item pill-1" role="presentation">
                                        <a class="nav-link active" id="pills-documents-tab" data-toggle="pill" href="#pills-documents" role="tab" aria-controls="pills-documents" aria-selected="true"><i class="far fa-file-alt mr-2"></i>Documents</a>
                                    </li>
                                    <li class="nav-item pill-2" role="presentation">
                                        <a class="nav-link" id="pills-images-tab" data-toggle="pill" href="#pills-images" role="tab" aria-controls="pills-images" aria-selected="false"><i class="far fa-image mr-2"></i>Images</a>
                                    </li>
                                </ul>
                                <div class="tab-content" id="pills-tabContent">
                                    <div class="tab-pane fade show active" id="pills-documents" role="tabpanel" aria-labelledby="pills-documents-tab">
                                        <div class="row">
                                            @foreach ($documents as $document)
                                                @if(preg_match_all('/application\/\w+/', \Storage::mimeType('documents/'.$document->filename)))
                                                    <div class="col-md-12 mb-3">
                                                        <div class="card bg-light border border-maroon rounded-lg">
                                                            <div class="card-body">
                                                                <div class="row mb-n2">
                                                                    <div class="col-md-4 mb-2">
                                                                        <strong>{{ $document->filename }}</strong>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        {{ $document->first_name." ".$document->last_name }}
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        @if ($document->status == 1)
                                                                                <a class="btn btn-warning btn-sm btn-disabled text-dark">Not Reviewed</a>
                                                                        @elseif($document->status == 2)
                                                                            <a class="btn btn-sm btn-success btn-disabled">Accepted</a>
                                                                        @elseif($document->status == 3)
                                                                            <a class="btn btn-sm btn-danger btn-disabled">Rejected</a>
                                                                        @endif
                                                                    </div>
                                                                    <div class="col-md-2 mb-2">
                                                                        <div class="d-inline-flex mr-1">
                                                                            <a href="{{ route('document.download', $document->filename) }}"  class="btn btn-success btn-sm"><i class="far fa-arrow-alt-circle-down"></i></a>
                                                                        </div>
                                                                        <div class="d-inline-flex">
                                                                            @if (\Auth::id() == $document->user_id)
                                                                                <form action="{{  route('professor.file.delete', $document->event_id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                                                    @csrf
                                                                                    <input type="hidden" name="filename" value="{{ $document->filename }}">
                                                                                    <button type="submit" class="btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>
                                                                                </form>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                    <div class="d-flex justify-content-around">
                                                                        <div class="text-truncate" style="max-width: 150px; width: 150px"></div>
                                                                        <div class="text-truncate" style="max-width: 150px; width: 150px"></div>
                                                                        <div class="text-truncate" style="max-width: 150px; width: 150px">
                                                                            
                                                                        </div>
                                                                    </div>

                                                                    <div class="d-flex justify-content-end" width="150px">
                                                                        
                                                                    </div>
                                                                </div> --}}
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="pills-images" role="tabpanel" aria-labelledby="pills-profile-tab">
                                        <div class="row">
                                            @foreach ($documents as $document)
                                                @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$document->filename)))
                                                    <div class="col-md-3 mb-3">
                                                        <div class="card bg-light border border-maroon rounded-lg">
                                                            <a href="{{ route('document.display', $document->filename) }}" data-lightbox="gallery" data-title="Submitted by: {{ $document->first_name." ".$document->last_name }}">
                                                                <img src="{{ route('document.display', $document->filename) }}" class="card-img-top img-resize"/>
                                                            </a>
                                                            <div class="card-body">
                                                                <table class="table table-sm my-n3 text-center">
                                                                    <tr>
                                                                        <th> 
                                                                            <small><strong>{{ $document->first_name." ".$document->last_name }}</strong></small>
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>
                                                                            @if ($document->status == 1)
                                                                                <a class="btn btn-warning btn-sm btn-disabled text-dark">Not Reviewed</a>
                                                                            @elseif($document->status == 2)
                                                                                <a class="btn btn-sm btn-success btn-disabled">Accepted</a>
                                                                            @elseif($document->status == 3)
                                                                                <a class="btn btn-sm btn-danger btn-disabled">Rejected</a>
                                                                            @endif
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>
                                                                            <a href="{{ route('document.download', $document->filename) }}"  class="btn btn-success btn-sm"><i class="far fa-arrow-alt-circle-down mr-2"></i> Download</a>                                                                                    
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th>
                                                                            @if (\Auth::id() == $document->user_id)
                                                                            <form action="{{  route('professor.file.delete', $document->event_id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                                                @csrf
                                                                                <input type="hidden" name="filename" value="{{ $document->filename }}">
                                                                                <button type="submit" class="btn btn-outline-danger btn-sm"><i class="far fa-trash-alt mr-2"></i> Remove</button>
                                                                            </form>
                                                                            @endif
                                                                        </th>
                                                                    </tr>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
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
        <script src="{{ asset('lightbox2/dist/js/lightbox-plus-jquery.js') }}"></script>
        {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/commonmark/0.28.1/commonmark.min.js"></script>
        <script src="{{ asset('dist/markdown-toolbar.js') }}"></script> --}}
        {{-- <script>
            $(document).ready(function() {
                $('#message').markdownToolbar();
            });
        </script> --}}
        <script>
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 4000);
        </script>
        <script>
            /*
            We want to preview images, so we need to register the Image Preview plugin
            */
            FilePond.registerPlugin(
	
                // encodes the file as base64 data
                FilePondPluginFileEncode,
                
                // validates the size of the file
                FilePondPluginFileValidateSize,
                
                // corrects mobile image orientation
                FilePondPluginImageExifOrientation,
                
                // previews dropped images
                FilePondPluginImagePreview,

                FilePondPluginFileValidateType,
                
            );

            // Create a FilePond instance
            const pondDocument = FilePond.create(document.querySelector('input[name="document[]"]'));
            // const pondImage = FilePond.create(document.querySelector('input[name="image[]"]'));
            pondDocument.setOptions({
                acceptedFileTypes: ['application/pdf', 'image/jpeg', 'image/png'],
                // stylePanelLayout: 'integrated',
                styleItemPanelAspectRatio: '1',
                // imagePreviewHeight: 250,
                server: {
                    process: {
                        url: "/upload",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    },
                    revert:{
                        url: "/remove",
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        }
                    },
                }
            });
            // pondImage.setOptions({
            //     acceptedFileTypes: ['image/*'],
            //     imagePreviewHeight: 50,
            //     server: {
            //         process: "/upload",
            //         revert: "/removeimage",
            //         headers: {
            //             'X-CSRF-TOKEN': '{{ csrf_token() }}',
            //         }
            //     }
            // });


        </script>
    @endpush
</x-app-layout>