<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research Details') }}
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
                            <div class="col-md-6">
                                <h4>Research Completed</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="dropdown">
                                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Options
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="{white-space: nowrap; }}">
                                        @switch($research->status_name)
                                            @case('New Commitment') @case('Ongoing')
                                                <a class="dropdown-item" href="{{ route('research.completed.create', $research->research_code) }}">Complete Research</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Research Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Completed')
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Update Complete Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code ) }}">Research Publication</a>
                                                <a class="dropdown-item" href="{{ route('research.presentation', $research->research_code ) }}">Research Presentation</a>
                                                <a class="dropdown-item" href="{{ route('research.copyright', $research->research_code ) }}">Research Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Research Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Completed & Published')
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Update Complete Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Update Research Publication</a>
                                                <a class="dropdown-item" href="{{ route('research.presentation', $research->research_code ) }}">Research Presentation</a>
                                                <a class="dropdown-item" href="{{ route('research.copyright', $research->research_code ) }}">Research Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.citation.create', $research->research_code) }}">Add Research Citation</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Research Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Completed & Presented')
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Update Complete Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code ) }}">Research Publication</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Update Research Presentation</a>
                                                <a class="dropdown-item" href="{{ route('research.copyright', $research->research_code ) }}">Research Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Research Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Completed, Presented, Published')
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Update Complete Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Update Research Publication</a>
                                                <a class="dropdown-item" href="{{ route('research.presentation', $research->research_code) }}">Update Research Presentation</a>
                                                <a class="dropdown-item" href="{{ route('research.copyright', $research->research_code ) }}">Research Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.citation.create', $research->research_code) }}">Add Research Citation</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Research Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Deferred')
                                                @break
                                            @default
                                                
                                        @endswitch
                                        <a class="dropdown-item" href="{{ route('research.edit', $research->research_code) }}">Update Research Info</a>
                                        <a class="dropdown-item text-danger " href="#">Delete</a>
                                    </div>
                                </div>
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
@endpush

</x-app-layout>