<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Show Partnership/ Linkages/ Network') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('partnership.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Partnership/ Linkages/ Network</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Colleges/Campus/Branch/Office to commit this accomplishment</label><span style="color: red;"> *</span>

                                    <select name="college_id" id="college" class="form-control custom-select form-validation"  required>
                                        <option value="" selected disabled>Choose...</option>
                                        @foreach ($colleges as $college)
                                        <option value="{{ $college->id }}" {{ ($college->id == $values['college_id']) ? 'selected': '' }}>{{ $college->name }}</option>
                                        @endforeach
                                       
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label>Department to commit this accomplishment</label><span style="color: red;"> *</span>

                                <select name="department_id" id="department" class="form-control custom-select form-validation" required>
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($departments as $department)
                                        <option value="{{ $department->id }}" {{ ($department->id == $values['department_id']) ? 'selected': '' }}>{{ $department->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @include('extension-programs.form', ['formFields' => $partnershipFields, 'value' => $values])
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
                                    @if (count($documents) > 0)
                                        @foreach ($documents as $document)
                                            @if(preg_match_all('/application\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                <div class="col-md-12 mb-3" id="doc-{{ $document['id'] }}">
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
                                    @if(count($documents) > 0)
                                        @foreach ($documents as $document)
                                            @if(preg_match_all('/image\/\w+/', \Storage::mimeType('documents/'.$document['filename'])))
                                                <div class="col-md-6 mb-3" id="doc-{{ $document['id'] }}">
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
    <script>
        $("#document").remove();
        $(document).ready(function(){
            $("input").prop("disabled", true);
            $("textarea").prop("disabled", true);
            $("select").prop("disabled", true);
        });
    </script>
    @endpush
</x-app-layout>