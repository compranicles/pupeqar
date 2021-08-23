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
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('professor.submissions.index') }}" class="btn btn-secondary mb-2"><i class="fas fa-arrow-left mr-2"></i> Back</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <h3 id="textHome" class="font-weight-bold text-center" style="color:maroon">Ongoing Advanced/Professional Study</h3>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <span class="d-block font-weight-bold">Department: </span>
                                <span class="d-block ml-4">{{ $department->name }}</span>
                            </div>
                            <div class="col-md-4">
                                <span class="d-block font-weight-bold">Degree/Program: </span>
                                <span class="d-block ml-4">{{ $ongoingadvanced->degree }}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 id=textHome style="color: maroon"><b>School</b></h5>
                            </div>
                            <div class="col-md-7 mb-1">
                                <span class="d-block font-weight-bold">Name of School: </span>
                                <span class="d-block ml-4">{{ $ongoingadvanced->school }}</span>
                            </div>
                            <div class="col-md-5">
                                <span class="d-block font-weight-bold">Program Accreditation Level/World Ranking/COE or COD: </span>
                                <span class="d-block ml-4">{{ $accrelevel->name }}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 id=textHome style="color: maroon"><b>Means of Educational Support</b></h5>
                            </div>
                            <div class="col-md-3 mb-1">
                                <span class="d-block font-weight-bold">Type of Support: </span>
                                <span class="d-block ml-4">{{ $supporttype->name }}</span>
                            </div>
                            <div class="col-md-7 mb-1">
                                <span class="d-block font-weight-bold">Name of Sponsor/Agency/Organization: </span>
                                <span class="d-block ml-4">{{ $ongoingadvanced->sponsor }}</span>
                            </div>
                            <div class="col-md-2">
                                <span class="d-block font-weight-bold">Amount: </span>
                                <span class="d-block ml-4">{{ $ongoingadvanced->amount }}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-lg-12">
                                <h5 id=textHome style="color: maroon"><b>Duration</b></h5>
                            </div>
                            <div class="col-md-4 mb-1">
                                <span class="d-block font-weight-bold">From: </span>
                                <span class="d-block ml-4">{{ date("F j, Y" , strtotime( $ongoingadvanced->date_started)) }}</span>
                            </div>
                            <div class="col-md-4">
                                <span class="d-block font-weight-bold">To: </span>
                                <span class="d-block ml-4">
                                    @if ($ongoingadvanced->present == 'on')
                                        Present
                                    @else
                                        {{ date("F j, Y" , strtotime( $ongoingadvanced->date_ended)) }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4 mb-1">
                                <span class="d-block font-weight-bold">Status: </span>
                                <span class="d-block ml-4">{{ $studystatus->name }}</span>
                            </div>
                            <div class="col-md-4 mb-1">
                                <span class="d-block font-weight-bold">Number of Units Earned: </span>
                                <span class="d-block ml-4">{{ $ongoingadvanced->units_earned }}</span>
                            </div>
                            <div class="col-md-4">
                                <span class="d-block font-weight-bold">Number of Units Currently Enrolled: </span>
                                <span class="d-block ml-4">{{ $ongoingadvanced->units_enrolled }}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <span class="d-block font-weight-bold">Description of Supporting Documents: </span>
                                <span class="d-block ml-4">{{ $ongoingadvanced->document_description }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5 id="textHome" style="color:maroon">Supporting Documents</h5>
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
                                                            <div class="col-md-2 mb-2">
                                                                <div class="d-inline-flex mr-1">
                                                                    <a href="{{ route('document.download', $document->filename) }}"  class="btn btn-success btn-sm"><i class="far fa-arrow-alt-circle-down"></i></a>
                                                                </div>
                                                            </div>
                                                        </div>
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
    @endpush
</x-app-layout>