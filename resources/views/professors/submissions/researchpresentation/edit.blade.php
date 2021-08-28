<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('C.3. Research Presentation > Edit') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('professor.submissions.researchpresentation.show', $researchpresentation->id) }}" class="btn btn-secondary mb-2 mr-2"><i class="fas fa-arrow-left mr-2"></i> Back</a>
                            </div>
                        </div>
                        <hr>
                        <form action="{{ route('professor.submissions.researchpresentation.update', $researchpresentation->id) }}" method="POST">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Department') }}" />

                                        <select name="department" id="department" class="form-control custom-select {{ $errors->has('department') ? 'is-invalid' : '' }}" autofocus autocomplete="department">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ ((old('department', $researchpresentation->department_id) == $department->id) ? 'selected' : '' )}}>{{ $department->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="department"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Classification') }}" />

                                        <select name="researchclass" id="researchclass" class="form-control custom-select {{ $errors->has('researchclass') ? 'is-invalid' : '' }}" autofocus autocomplete="researchclass">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($researchclasses as $researchclass)
                                            <option value="{{ $researchclass->id }}" {{ ((old('researchclass', $researchpresentation->research_class_id) == $researchclass->id) ? 'selected' : '' )}}>{{ $researchclass->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="researchclass"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Category') }}" />

                                        <select name="researchcategory" id="researchcategory" class="form-control custom-select {{ $errors->has('researchcategory') ? 'is-invalid' : '' }}" autofocus autocomplete="researchcategory">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($researchcategories as $researchcategory)
                                            <option value="{{ $researchcategory->id }}" {{ ((old('researchcategory', $researchpresentation->research_category_id) == $researchcategory->id) ? 'selected' : '' )}}>{{ $researchcategory->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="researchcategory"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('University Research Agenda') }}" />

                                        <select name="researchagenda" id="researchagenda" class="form-control custom-select {{ $errors->has('researchagenda') ? 'is-invalid' : '' }}" autofocus autocomplete="researchagenda">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($researchagendas as $researchagenda)
                                            <option value="{{ $researchagenda->id }}" {{ ((old('researchagenda', $researchpresentation->research_agenda_id) == $researchagenda->id) ? 'selected' : '' )}}>{{ $researchagenda->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="researchagenda"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Title of Research') }}" />

                                        <textarea class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" name="title" cols="30" rows="5" autofocus autocomplete="title">{{ old('title', $researchpresentation->title) }}</textarea>

                                        <x-jet-input-error for="title"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Researcher/s (Surname, First Name M.I.)') }}" />

                                        <textarea class="form-control {{ $errors->has('researchers') ? 'is-invalid' : '' }}" name="researchers" cols="30" rows="5" autofocus autocomplete="researchers">{{ old('researchers', $researchpresentation->researchers) }}</textarea>


                                        <x-jet-input-error for="researchers"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Nature of Involvement') }}" />

                                        <select name="researchinvolve" id="researchinvolve" class="form-control custom-select {{ $errors->has('researchinvolve') ? 'is-invalid' : '' }}" autofocus autocomplete="researchinvolve">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($researchinvolves as $researchinvolve)
                                            <option value="{{ $researchinvolve->id }}" {{ ((old('researchinvolve', $researchpresentation->research_involve_id) == $researchinvolve->id) ? 'selected' : '' )}}>{{ $researchinvolve->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="researchinvolve"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Type of Research') }}" />

                                        <select name="researchtype" id="researchtype" class="form-control custom-select {{ $errors->has('researchtype') ? 'is-invalid' : '' }}" autofocus autocomplete="researchtype">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($researchtypes as $researchtype)
                                            <option value="{{ $researchtype->id }}" {{ ((old('researchtype', $researchpresentation->research_type_id) == $researchtype->id) ? 'selected' : '' )}}>{{ $researchtype->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="researchtype"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Keywords (at least five (5) keywords)') }}" />

                                        <textarea class="form-control {{ $errors->has('keywords') ? 'is-invalid' : '' }}" name="keywords" cols="30" rows="5" autofocus autocomplete="title">{{ old('keywords', $researchpresentation->keywords) }}</textarea>

                                        <x-jet-input-error for="keywords"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Type of Funding') }}" />

                                        <select name="fundingtype" id="fundingtype" class="form-control custom-select {{ $errors->has('fundingtype') ? 'is-invalid' : '' }}" autofocus autocomplete="fundingtype">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($fundingtypes as $fundingtype)
                                            <option value="{{ $fundingtype->id }}" {{ ((old('fundingtype', $researchpresentation->funding_type_id) == $fundingtype->id) ? 'selected' : '' )}}>{{ $fundingtype->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="fundingtype"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Amount of Funding') }}" />

                                        <x-jet-input :value="old('fundingamount', $researchpresentation->funding_amount)" class="{{ $errors->has('fundingamount') ? 'is-invalid' : '' }}" type="text" id="fundingamount" name="fundingamount" autofocus autocomplete="fundingamount" />

                                        <x-jet-input-error for="fundingamount"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Funding Agency') }}" />

                                        <x-jet-input :value="old('fundingagency', $researchpresentation->funding_agency)" class="{{ $errors->has('fundingagency') ? 'is-invalid' : '' }}" type="text" id="fundingagency" name="fundingagency" autofocus autocomplete="fundingagency" />

                                        <x-jet-input-error for="fundingagency"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Actual Date Started') }}" />

                                        <x-jet-input :value="old('datestarted', $researchpresentation->date_started)" class="{{ $errors->has('datestarted') ? 'is-invalid' : '' }}" type="text" id="datestarted " name="datestarted" autofocus autocomplete="datestarted " />

                                        <x-jet-input-error for="datestarted"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Target Date of Completion') }}" />

                                        <x-jet-input :value="old('datetargeted', $researchpresentation->date_targeted)" class="{{ $errors->has('datetargeted') ? 'is-invalid' : '' }}" type="text" id="datetargeted" name="datetargeted" autofocus autocomplete="datetargeted" />

                                        <x-jet-input-error for="datetargeted"></x-jet-input-error>

                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Date Completed') }}" />

                                        <x-jet-input :value="old('datecompleted', $researchpresentation->date_completed)" class="{{ $errors->has('datecompleted') ? 'is-invalid' : '' }}" type="text" id="datecompleted" name="datecompleted" autofocus autocomplete="datecompleted" />

                                        <x-jet-input-error for="datecompleted"></x-jet-input-error>

                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Conference Title') }}" />

                                        <x-jet-input :value="old('conferencetitle', $researchpresentation->conference_title)" class="{{ $errors->has('conferencetitle') ? 'is-invalid' : '' }}" type="text" id="conferencetitle" name="conferencetitle" autofocus autocomplete="conferencetitle" />

                                        <x-jet-input-error for="conferencetitle"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Organizer') }}" />

                                        <x-jet-input :value="old('organizer', $researchpresentation->organizer)" class="{{ $errors->has('organizer') ? 'is-invalid' : '' }}" type="text" id="organizer" name="organizer" autofocus autocomplete="organizer" />

                                        <x-jet-input-error for="organizer"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Venue') }}" />

                                        <x-jet-input :value="old('venue', $researchpresentation->venue)" class="{{ $errors->has('venue') ? 'is-invalid' : '' }}" type="text" id="venue" name="venue" autofocus autocomplete="venue" />

                                        <x-jet-input-error for="venue"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Date of Presentation') }}" />

                                        <x-jet-input :value="old('datepresented', $researchpresentation->date_presented)" class="{{ $errors->has('datepresented') ? 'is-invalid' : '' }}" type="text" id="datepresented" name="datepresented" autofocus autocomplete="datepresented" />

                                        <x-jet-input-error for="datepresented"></x-jet-input-error>

                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Level') }}" />

                                        <select name="researchlevel" id="researchlevel" class="form-control custom-select {{ $errors->has('researchlevel') ? 'is-invalid' : '' }}" autofocus autocomplete="researchlevel">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($researchlevels as $researchlevel)
                                            <option value="{{ $researchlevel->id }}" {{ ((old('researchlevel', $researchpresentation->research_level_id) == $researchlevel->id) ? 'selected' : '' )}}>{{ $researchlevel->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="researchlevel"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-6">
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
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Description of Supporting Documents') }}" />

                                        <textarea class="form-control {{ $errors->has('documentdescription') ? 'is-invalid' : '' }}" name="documentdescription" cols="30" rows="5" autofocus autocomplete="documentdescription">{{ old('documentdescription', $researchpresentation->document_description) }}</textarea>

                                        <x-jet-input-error for="documentdescription"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="mb-0">
                                <div class="d-flex justify-content-end align-items-baseline">
                                    <a href="{{ route('professor.submissions.index') }}" class="btn btn-outline-danger mr-2">
                                        CANCEL
                                    </a>
                                    <x-jet-button>
                                        {{ __('Submit') }}
                                    </x-jet-button>
                                </div>
                            </div>
                        </form>
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
                                                            <div class="row mb-3">
                                                                <div class="col-md-12">
                                                                    <div class="embed-responsive embed-responsive-1by1">
                                                                        <iframe  src="{{ route('document.view', $document->filename) }}" width="100%" height="500px"></iframe>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <form action="{{   route('professor.researchpresentation.file.delete', $document->submission_id)  }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                                        @csrf
                                                                        <input type="hidden" name="filename" value="{{ $document->filename }}">
                                                                        <button type="submit" class="btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i> Delete</button>
                                                                    </form>
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
                                                                        <form action="{{  route('professor.researchpresentation.file.delete', $document->submission_id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                                            @csrf
                                                                            <input type="hidden" name="filename" value="{{ $document->filename }}">
                                                                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="far fa-trash-alt mr-2"></i> Remove</button>
                                                                        </form>
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
    @push('scripts')
        <script src="{{ asset('js/litepicker.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/plugins/mobilefriendly.js"></script>
        <script>
          
            const today = new Date();

            const picker = new Litepicker ({
                element: document.getElementById('datestarted'),
                elementEnd: document.getElementById('datetargeted'),
                singleMode: false,
                // allowRepick: true,
                resetButton: true,
                // numberOfColumns: 2,
                // numberOfMonths: 2,
                dropdowns: {
                    "minYear":2020,
                    "maxYear":null,
                    "months":true,
                    "years":true,
                },
                // firstDay : 0,
                plugins: ['mobilefriendly'],
                mobilefriendly: {
                  breakpoint: 480,
                },
            });

            const picker2 = new Litepicker({
                element: document.getElementById('datecompleted'),
                singleMode: true,
                resetButton: true,
                dropdowns: {
                    "minYear":2020,
                    "maxYear":null,
                    "months":true,
                    "years":true,
                },
                plugins: ['mobilefriendly'],
                mobilefriendly: {
                  breakpoint: 480,
                },
            });

            const picker3 = new Litepicker({
                element: document.getElementById('datepresented'),
                singleMode: true,
                resetButton: true,
                dropdowns: {
                    "minYear":2020,
                    "maxYear":null,
                    "months":true,
                    "years":true,
                },
                plugins: ['mobilefriendly'],
                mobilefriendly: {
                  breakpoint: 480,
                },
            });

            // picker.setDateRange(today, today, false);
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
                // styleItemPanelAspectRatio: '1',
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
        <script>
            var present = document.getElementById('present');
            var toinput = document.getElementById('date-end');
            
            if(document.getElementById("present").checked){
                toinput.disabled = true;
            }

            // when unchecked or checked, run the function
            present.onchange = function(){
                if(this.checked){
                    toinput.disabled = true;
                } else {
                    toinput.disabled = false;
                }
            }
        </script>
    @endpush
</x-app-layout>