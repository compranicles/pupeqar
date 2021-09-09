<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('E.2. Extension Program, Project and Activity (Ongoing and Completed) > Edit') }}
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
                            <div class="col-md-12">
                                <a href="{{ route('hap.review.extensionprogram.show', $extensionprogram->id) }}" class="btn btn-secondary mb-2 mr-2"><i class="fas fa-arrow-left mr-2"></i> Back</a>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <span class="font-weight-bold h5">NAME: </span><span class="h5">{{ strtoupper($submission->last_name.', '.$submission->first_name.' '.$submission->middle_name) }}</span>
                            </div>
                        </div>
                        <hr>
                        <form action="{{ route('hap.review.extensionprogram.update', $extensionprogram->id) }}" method="POST">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Department') }}" />

                                        <select name="department" id="department" class="form-control custom-select {{ $errors->has('department') ? 'is-invalid' : '' }}" autofocus autocomplete="department">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ ((old('department', $extensionprogram->department_id) == $department->id) ? 'selected' : '' )}}>{{ $department->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="department"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Title of Extension Program') }}" />

                                        <x-jet-input :value="old('programtitle', $extensionprogram->program)" class="{{ $errors->has('programtitle') ? 'is-invalid' : '' }}" type="text" name="programtitle" autofocus autocomplete="programtitle" />

                                        <x-jet-input-error for="programtitle"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Title of Extension Project') }}" />

                                        <x-jet-input :value="old('projecttitle', $extensionprogram->project)" class="{{ $errors->has('projecttitle') ? 'is-invalid' : '' }}" type="text" name="projecttitle" autofocus autocomplete="projecttitle" />

                                        <x-jet-input-error for="projecttitle"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Title of Extension Activity') }}" />

                                        <x-jet-input :value="old('activitytitle', $extensionprogram->activity)" class="{{ $errors->has('activitytitle') ? 'is-invalid' : '' }}" type="text" name="activitytitle" autofocus autocomplete="activitytitle" />

                                        <x-jet-input-error for="activitytitle"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Nature of Involvement') }}" />

                                        <select name="extensionnature" id="extensionnature" class="form-control custom-select {{ $errors->has('extensionnature') ? 'is-invalid' : '' }}" autofocus autocomplete="extensionnature">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($extensionnatures as $extensionnature)
                                            <option value="{{ $extensionnature->id }}" {{ ((old('extensionnature', $extensionprogram->extension_nature_id) == $extensionnature->id) ? 'selected' : '' )}}>{{ $extensionnature->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="extensionnature"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Source of Fund') }}" />

                                        <select name="fundingtype" id="fundingtype" class="form-control custom-select {{ $errors->has('fundingtype') ? 'is-invalid' : '' }}" autofocus autocomplete="fundingtype">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($fundingtypes as $fundingtype)
                                            <option value="{{ $fundingtype->id }}" {{ ((old('fundingtype', $extensionprogram->funding_type_id) == $fundingtype->id) ? 'selected' : '' )}}>{{ $fundingtype->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="fundingtype"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Amount of Fund') }}" />

                                        <x-jet-input :value="old('fundingamount', $extensionprogram->funding_amount)" class="{{ $errors->has('fundingamount') ? 'is-invalid' : '' }}" type="text" name="fundingamount" autofocus autocomplete="fundingamount" />

                                        <x-jet-input-error for="fundingamount"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Classification of the Extension Activity') }}" />

                                        <select name="extensionclass" id="extensionclass" class="form-control custom-select {{ $errors->has('extensionclass') ? 'is-invalid' : '' }}" autofocus autocomplete="extensionclass">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($extensionclasses as $extensionclass)
                                            <option value="{{ $extensionclass->id }}" {{ ((old('extensionclass', $extensionprogram->extension_class_id) == $extensionclass->id) ? 'selected' : '' )}}>{{ $extensionclass->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="extensionclass"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Others') }}" />

                                        <x-jet-input :value="old('others', $extensionprogram->others)" class="{{ $errors->has('others') ? 'is-invalid' : '' }}" type="text" name="others" autofocus autocomplete="others" />

                                        <x-jet-input-error for="others"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Partnership Levels') }}" />

                                        <select name="level" id="level" class="form-control custom-select {{ $errors->has('level') ? 'is-invalid' : '' }}" autofocus autocomplete="level">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($levels as $level)
                                            <option value="{{ $level->id }}" {{ ((old('level', $extensionprogram->level_id) == $level->id) ? 'selected' : '' )}}>{{ $level->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="level"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 id=textHome style="color: maroon"><b>Project Duration</b></h5>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('From ') }}" />

                                        <x-jet-input :value="old('datestarted', $extensionprogram->date_started)" class="{{ $errors->has('datestarted') ? 'is-invalid' : '' }}" type="text" id="datestarted" name="datestarted" autofocus autocomplete="datestarted" />

                                        <x-jet-input-error for="datestarted"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4 ">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('To') }}" />

                                        <x-jet-input value="{{ old('dateended', $extensionprogram->date_ended) == '' ? date('Y-m-d') : old('dateended', $extensionprogram->date_ended) }}" class="{{ $errors->has('dateended') ? 'is-invalid' : '' }}" type="text" id="dateended" name="dateended" autofocus autocomplete="dateended" />

                                        <x-jet-input-error for="dateended"></x-jet-input-error>

                                    </div>
                                    <div class="form-check">
                                        
                                        <input class="{{ $errors->has('present') ? 'is-invalid' : '' }}" type="checkbox" id="present" name="present" {{  $extensionprogram->present == 'on' ? 'checked' : '' }} autofocus autocomplete="present" >
                                            
                                        <x-jet-label value="{{ __('Present') }}" class="form-check-label"/>

                                        <x-jet-input-error for="present"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Status') }}" />

                                        <select name="status" id="status" class="form-control custom-select {{ $errors->has('status') ? 'is-invalid' : '' }}" autofocus autocomplete="status">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ ((old('status', $extensionprogram->status_id) == $status->id) ? 'selected' : '' )}}>{{ $status->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="status"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Place/Venue') }}" />

                                        <x-jet-input :value="old('venue', $extensionprogram->venue)" class="{{ $errors->has('venue') ? 'is-invalid' : '' }}" type="text" name="venue" autofocus autocomplete="venue" />

                                        <x-jet-input-error for="venue"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('No. of Trainees') }}" />

                                        <x-jet-input :value="old('trainees', $extensionprogram->trainees)" class="{{ $errors->has('trainees') ? 'is-invalid' : '' }}" type="text" name="trainees" autofocus autocomplete="trainees" />

                                        <x-jet-input-error for="trainees"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Classification of Trainees') }}" />

                                        <x-jet-input :value="old('traineesclass',$extensionprogram->trainees_class)" class="{{ $errors->has('traineesclass') ? 'is-invalid' : '' }}" type="text" name="traineesclass" autofocus autocomplete="traineesclass" />

                                        <x-jet-input-error for="traineesclass"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 id=textHome style="color: maroon"><b>Total No. of Trainees/Beneficiaries Who Rated the Quality of Extension Service </b></h5>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Poor') }}" />

                                        <x-jet-input :value="old('qualitypoor', $extensionprogram->quality_poor)" class="{{ $errors->has('qualitypoor') ? 'is-invalid' : '' }}" type="number" name="qualitypoor" autofocus autocomplete="qualitypoor" />

                                        <x-jet-input-error for="qualitypoor"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Fair') }}" />

                                        <x-jet-input :value="old('qualityfair',$extensionprogram->quality_fair)" class="{{ $errors->has('qualityfair') ? 'is-invalid' : '' }}" type="number" name="qualityfair" autofocus autocomplete="qualityfair" />

                                        <x-jet-input-error for="qualityfair"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Satisfactory') }}" />

                                        <x-jet-input :value="old('qualitysatisfactory', $extensionprogram->quality_satisfactory)" class="{{ $errors->has('qualitysatisfactory') ? 'is-invalid' : '' }}" type="number" name="qualitysatisfactory" autofocus autocomplete="qualitysatisfactory" />

                                        <x-jet-input-error for="qualitysatisfactory"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Very Satisfactory') }}" />

                                        <x-jet-input :value="old('qualityvsatisfactory', $extensionprogram->quality_vsatisfactory)" class="{{ $errors->has('qualityvsatisfactory') ? 'is-invalid' : '' }}" type="number" name="qualityvsatisfactory" autofocus autocomplete="qualityvsatisfactory" />

                                        <x-jet-input-error for="qualityvsatisfactory"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Outstanding') }}" />

                                        <x-jet-input :value="old('qualityoutstanding', $extensionprogram->quality_outstanding)" class="{{ $errors->has('qualityoutstanding') ? 'is-invalid' : '' }}" type="number" name="qualityoutstanding" autofocus autocomplete="qualityoutstanding" />

                                        <x-jet-input-error for="qualityoutstanding"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 id=textHome style="color: maroon"><b>Total No. of Trainees/Beneficiaries Who Rated the Timeliness of Extension Service </b></h5>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Poor') }}" />

                                        <x-jet-input :value="old('timelinesspoor', $extensionprogram->timeliness_poor)" class="{{ $errors->has('timelinesspoor') ? 'is-invalid' : '' }}" type="number" name="timelinesspoor" autofocus autocomplete="timelinesspoor" />

                                        <x-jet-input-error for="timelinesspoor"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Fair') }}" />

                                        <x-jet-input :value="old('timelinessfair',$extensionprogram->timeliness_fair)" class="{{ $errors->has('timelinessfair') ? 'is-invalid' : '' }}" type="number" name="timelinessfair" autofocus autocomplete="timelinessfair" />

                                        <x-jet-input-error for="timelinessfair"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Satisfactory') }}" />

                                        <x-jet-input :value="old('timelinesssatisfactory', $extensionprogram->timeliness_satisfactory)" class="{{ $errors->has('timelinesssatisfactory') ? 'is-invalid' : '' }}" type="number" name="timelinesssatisfactory" autofocus autocomplete="timelinesssatisfactory" />

                                        <x-jet-input-error for="timelinesssatisfactory"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Very Satisfactory') }}" />

                                        <x-jet-input :value="old('timelinessvsatisfactory', $extensionprogram->timeliness_vsatisfactory)" class="{{ $errors->has('timelinessvsatisfactory') ? 'is-invalid' : '' }}" type="number" name="timelinessvsatisfactory" autofocus autocomplete="timelinessvsatisfactory" />

                                        <x-jet-input-error for="timelinessvsatisfactory"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Outstanding') }}" />

                                        <x-jet-input :value="old('timelinessoutstanding', $extensionprogram->timeliness_outstanding)" class="{{ $errors->has('timelinessoutstanding') ? 'is-invalid' : '' }}" type="number" name="timelinessoutstanding" autofocus autocomplete="timelinessoutstanding" />

                                        <x-jet-input-error for="timelinessoutstanding"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Number of Hours') }}" />

                                        <x-jet-input :value="old('hours',$extensionprogram->hours)" class="{{ $errors->has('hours') ? 'is-invalid' : '' }}" type="number" name="hours" autofocus autocomplete="hours" />

                                        <x-jet-input-error for="hours"></x-jet-input-error>
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
                                        <p class="mt-1"><small>Maximum size per file: 5MB. Maximum number of files: 10.</small></p>
                                        <p class="mt-n4"><small>Accepts PDF, JPEG, and PNG file formats.</small></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Description of Supporting Documents') }}" />

                                        <textarea class="form-control {{ $errors->has('documentdescription') ? 'is-invalid' : '' }}" name="documentdescription" cols="30" rows="5" autofocus autocomplete="documentdescription">{{ old('documentdescription', $extensionprogram->document_description) }}</textarea>

                                        <x-jet-input-error for="documentdescription"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="mb-0">
                                <div class="d-flex justify-content-end align-items-baseline">
                                    <a href="{{ route('hap.review.extensionprogram.show', $extensionprogram->id) }}" class="btn btn-outline-danger mr-2">
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
                                                                    <form action="{{   route('hap.extensionprogram.file.delete', $document->submission_id)  }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                                                                        <form action="{{  route('hap.extensionprogram.file.delete', $document->submission_id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
                elementEnd: document.getElementById('dateended'),
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
            var toinput = document.getElementById('dateended');

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