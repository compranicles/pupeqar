<x-app-layout>
    @section('title', 'Research & Book Chapter |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->id, 'research_status' => $research->status])
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                {{-- Success Message --}}
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>
                @endif
                @if ($message = Session::get('cannot_access'))
                    <div class="alert alert-danger alert-index">
                        {{ $message }}
                    </div>
                @endif
                @if ($research->id == $firstResearch['id'])
                    @if ($research->nature_of_involvement != 224 )
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-lightbulb-fill"></i> <strong>Reminder: </strong>Click <strong>Tag Co-Researchers</strong> button to check if the co-researchers already confirm their involvement <strong>before submitting</strong>.
                        </div>
                    @endif
                    <div class="alert alert-info" role="alert">
                        <i class="bi bi-lightbulb-fill"></i> <strong>Reminder: </strong>Click <strong>Options</strong> to proceed research completion, presentation, publication, copyright, utilization, citation.
                    </div>
                @else
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-lightbulb-fill"></i> <strong>Reminder: Wait your lead to submit the research before you submit</strong>.
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Research Registration - {{ $research->research_code }}</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        {{-- @if ($research->nature_of_involvement == 11)
                                            <a class="btn btn-secondary btn-sm mr-1" href="{{ route('research.manage-researchers', $research->research_code) }}">Manage Researchers</a>
                                        @else --}}
                                            {{-- <a class="btn btn-secondary btn-sm mr-1" href="{{ route('research.manage-researchers', $research->research_code) }}"></a>
                                        @endif --}}
                                        <!-- Submit buttons -->
                                        @if ($submissionStatus[1][$value['id']] == 0)
                                            <a href="{{ url('submissions/check/1/'.$research->id) }}" class="btn btn-sm btn-primary mr-3">Submit Registered Research</a>
                                        @elseif ($submissionStatus[1][$value['id']] == 1)
                                            <a href="{{ url('submissions/check/1/'.$research->id) }}" class="btn btn-sm btn-success mr-3">Registered Research Submitted {{ $submitRole[$research->id] == 'f' ? 'as Faculty' : 'as Admin' }}</a>
                                        @elseif ($submissionStatus[1][$value['id']] == 2)
                                            <a href="{{ route('research.edit', $research->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center mr-3"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                        @endif 

                                        @if ($research->nature_of_involvement != 224 && $research->id == $firstResearch['id'])
                                            <a href="{{ route('research.invite.index', $research->id) }}" class="btn btn-primary btn-sm mr-3"><i class="bi bi-person-plus-fill mr-1"></i> Tag Co-Researchers</a>
                                        @endif
                                        @include('research.options', ['research_id' => $research->id, 'research_status' => $research->status, 'involvement' => $research->nature_of_involvement, 'research_code' => $research->research_code])
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        @include('show', ['formFields' => $researchFields, 'value' => $value])
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
   
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
@endpush

</x-app-layout>