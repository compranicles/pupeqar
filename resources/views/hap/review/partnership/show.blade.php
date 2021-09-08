<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('View Submission') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('hap.review.index') }}" class="btn btn-secondary mb-2 mr-2"><i class="fas fa-arrow-left mr-2"></i> Back</a>
                                <a href="{{ route('hap.review.partnership.edit', $partnership->id) }}"  class="btn btn-primary mb-2 mr-2"><i class="far fa-edit"></i> Edit</a>
                                {{-- <button type="button" class="btn btn-danger mb-2 mr-2" data-toggle="modal" data-target="#deleteModal">
                                    <i class="far fa-trash-alt"></i> Delete
                                </button> --}}
                            </div>
                            <div class="col-md-6 text-md-right">
                                <button type="button" class="btn btn-outline-success mb-2 mr-2" data-toggle="modal" data-target="#acceptModal">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                                <button type="button" class="btn btn-outline-danger mb-2" data-toggle="modal" data-target="#rejectModal">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h3 id="textHome" class="font-weight-bold text-center" style="color:maroon">Partnership/Linkages/Network</h3>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-1">
                                                <span class="d-block font-weight-bold">Department: </span>
                                                <span class="d-block ml-4">{{ $department->name }}</span>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <span class="d-block font-weight-bold">Title: </span>
                                                <span class="d-block ml-4">{{ $partnership->title }}</span>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <span class="d-block font-weight-bold">Type of Partner Institution: </span>
                                                <span class="d-block ml-4">{{ $partnertype->name }}</span>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <span class="d-block font-weight-bold">Nature of Collaboration: </span>
                                                <span class="d-block ml-4">{{ $collabnature->name }}</span>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <span class="d-block font-weight-bold">Deliverable/Desired Output: </span>
                                                <span class="d-block ml-4">{{ $collabdeliver->name }}</span>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <span class="d-block font-weight-bold">Target Beneficiaries: </span>
                                                <span class="d-block ml-4">{{ $targetbeneficiary->name }}</span>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <span class="d-block font-weight-bold">Level: </span>
                                                <span class="d-block ml-4">{{ $level->name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12 mb-1">
                                                <span class="d-block font-weight-bold">From: </span>
                                                <span class="d-block ml-4">{{ date("F j, Y" , strtotime( $partnership->date_started)) }}</span>
                                            </div>
                                            <div class="col-md-12 mb-1">
                                                <span class="d-block font-weight-bold">To: </span>
                                                <span class="d-block ml-4">
                                                    @if ($partnership->present == 'on')
                                                        Present
                                                    @else
                                                        {{ date("F j, Y" , strtotime( $partnership->date_ended)) }}
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <span class="d-block font-weight-bold">Description of Supporting Documents: </span>
                                <span class="d-block ml-4">{{ $partnership->document_description }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
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
                                            @if(preg_match_all('/application\/\w+/', \Storage::mimeType('documents/'.$document->filename)))
                                                <div class="col-md-12 mb-3">
                                                    <div class="card bg-light border border-maroon rounded-lg">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class="embed-responsive embed-responsive-1by1">
                                                                        <iframe  src="{{ route('document.view', $document->filename) }}" width="100%" height="500px"></iframe>
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
                                    @if (count($documents) > 0)
                                        @foreach ($documents as $document)
                                            @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$document->filename)))
                                                <div class="col-md-6 mb-3">
                                                    <div class="card bg-light border border-maroon rounded-lg">
                                                        <a href="{{ route('document.display', $document->filename) }}" data-lightbox="gallery" data-title="{{ $document->filename }}">
                                                            <img src="{{ route('document.display', $document->filename) }}" class="card-img-top img-resize"/>
                                                        </a>
                                                        <div class="card-body">
                                                            <table class="table table-sm my-n3 text-center">
                                                                <tr>
                                                                    <th>
                                                                        <a href="{{ route('document.download', $document->filename) }}"  class="btn btn-success btn-sm"><i class="far fa-arrow-alt-circle-down mr-2"></i> Download</a>                                                                                    
                                                                    </th>
                                                                </tr>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="col-md-4 offset-md-4">
                                            <h6 class="text-center">No Images Attached</h6>
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
    

    {{-- Delete Modal --}}
    
    
    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Submission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">Are you sure you want to delete this submission?</h5>
                    <form action="{{ route('hap.review.partnership.destroy', $partnership->id) }}" method="POST">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger mb-2 mr-2">Delete</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Accept Modal --}}
    <div class="modal fade" id="acceptModal" tabindex="-1" aria-labelledby="acceptModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="acceptModalLabel">Delete Submission</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">Are you sure you want to delete this submission?</h5>
                <form action="" method="POST">
                    @csrf
                {{-- Replace with something in the future --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success mb-2 mr-2">Accept</button>
                </form>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="{{ asset('lightbox2/dist/js/lightbox-plus-jquery.js') }}"></script>
        <script>
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 4000);
        </script>
    @endpush
</x-app-layout>