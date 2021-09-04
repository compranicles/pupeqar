<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('E.2. Extension Program, Project and Activity (Ongoing and Completed) > Create') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('professor.submissions.index') }}" class="btn btn-secondary mb-2 mr-2"><i class="fas fa-arrow-left mr-2"></i> Back</a>
                            </div>
                        </div>
                        <hr>
                        <form action="{{ route('professor.submissions.extensionprogram.store') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Department') }}" />

                                        <select name="department" id="department" class="form-control custom-select {{ $errors->has('department') ? 'is-invalid' : '' }}" autofocus autocomplete="department">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ ((old('department') == $department->id) ? 'selected' : '' )}}>{{ $department->name }}</option>    
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

                                        <x-jet-input :value="old('programtitle')" class="{{ $errors->has('programtitle') ? 'is-invalid' : '' }}" type="text" name="programtitle" autofocus autocomplete="programtitle" />

                                        <x-jet-input-error for="programtitle"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Title of Extension Project') }}" />

                                        <x-jet-input :value="old('projecttitle')" class="{{ $errors->has('projecttitle') ? 'is-invalid' : '' }}" type="text" name="projecttitle" autofocus autocomplete="projecttitle" />

                                        <x-jet-input-error for="projecttitle"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Title of Extension Activity') }}" />

                                        <x-jet-input :value="old('activitytitle')" class="{{ $errors->has('activitytitle') ? 'is-invalid' : '' }}" type="text" name="activitytitle" autofocus autocomplete="activitytitle" />

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
                                            <option value="{{ $extensionnature->id }}" {{ ((old('extensionnature') == $extensionnature->id) ? 'selected' : '' )}}>{{ $extensionnature->name }}</option>    
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
                                            <option value="{{ $fundingtype->id }}" {{ ((old('fundingtype') == $fundingtype->id) ? 'selected' : '' )}}>{{ $fundingtype->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="fundingtype"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Amount of Fund') }}" />

                                        <x-jet-input :value="old('fundingamount')" class="{{ $errors->has('fundingamount') ? 'is-invalid' : '' }}" type="text" name="fundingamount" autofocus autocomplete="fundingamount" />

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
                                            <option value="{{ $extensionclass->id }}" {{ ((old('extensionclass') == $extensionclass->id) ? 'selected' : '' )}}>{{ $extensionclass->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="extensionclass"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Others') }}" />

                                        <x-jet-input :value="old('others')" class="{{ $errors->has('others') ? 'is-invalid' : '' }}" type="text" name="others" autofocus autocomplete="others" />

                                        <x-jet-input-error for="others"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Partnership Levels') }}" />

                                        <select name="level" id="level" class="form-control custom-select {{ $errors->has('level') ? 'is-invalid' : '' }}" autofocus autocomplete="level">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($levels as $level)
                                            <option value="{{ $level->id }}" {{ ((old('level') == $level->id) ? 'selected' : '' )}}>{{ $level->name }}</option>    
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

                                        <x-jet-input :value="old('datestarted')" class="{{ $errors->has('datestarted') ? 'is-invalid' : '' }}" type="text" id="datestarted" name="datestarted" autofocus autocomplete="datestarted" />

                                        <x-jet-input-error for="datestarted"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4 ">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('To') }}" />

                                        <x-jet-input :value="old('dateended')" class="{{ $errors->has('dateended') ? 'is-invalid' : '' }}" type="text" id="dateended" name="dateended" autofocus autocomplete="dateended" />

                                        <x-jet-input-error for="dateended"></x-jet-input-error>

                                    </div>
                                    <div class="form-check">
                                        
                                        <x-jet-checkbox :value="old('present')" class="{{ $errors->has('present') ? 'is-invalid' : '' }}" id="present" name="present" autofocus autocomplete="present" />
                                            
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
                                            <option value="{{ $status->id }}" {{ ((old('status') == $status->id) ? 'selected' : '' )}}>{{ $status->name }}</option>    
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

                                        <x-jet-input :value="old('venue')" class="{{ $errors->has('venue') ? 'is-invalid' : '' }}" type="text" name="venue" autofocus autocomplete="venue" />

                                        <x-jet-input-error for="venue"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('No. of Trainees') }}" />

                                        <x-jet-input :value="old('trainees')" class="{{ $errors->has('trainees') ? 'is-invalid' : '' }}" type="text" name="trainees" autofocus autocomplete="trainees" />

                                        <x-jet-input-error for="trainees"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Classification of Trainees') }}" />

                                        <x-jet-input :value="old('traineesclass')" class="{{ $errors->has('traineesclass') ? 'is-invalid' : '' }}" type="text" name="traineesclass" autofocus autocomplete="traineesclass" />

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

                                        <x-jet-input :value="old('qualitypoor')" class="{{ $errors->has('qualitypoor') ? 'is-invalid' : '' }}" type="number" name="qualitypoor" autofocus autocomplete="qualitypoor" />

                                        <x-jet-input-error for="qualitypoor"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Fair') }}" />

                                        <x-jet-input :value="old('qualityfair')" class="{{ $errors->has('qualityfair') ? 'is-invalid' : '' }}" type="number" name="qualityfair" autofocus autocomplete="qualityfair" />

                                        <x-jet-input-error for="qualityfair"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Satisfactory') }}" />

                                        <x-jet-input :value="old('qualitysatisfactory')" class="{{ $errors->has('qualitysatisfactory') ? 'is-invalid' : '' }}" type="number" name="qualitysatisfactory" autofocus autocomplete="qualitysatisfactory" />

                                        <x-jet-input-error for="qualitysatisfactory"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Very Satisfactory') }}" />

                                        <x-jet-input :value="old('qualityvsatisfactory')" class="{{ $errors->has('qualityvsatisfactory') ? 'is-invalid' : '' }}" type="number" name="qualityvsatisfactory" autofocus autocomplete="qualityvsatisfactory" />

                                        <x-jet-input-error for="qualityvsatisfactory"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Outstanding') }}" />

                                        <x-jet-input :value="old('qualityoutstanding')" class="{{ $errors->has('qualityoutstanding') ? 'is-invalid' : '' }}" type="number" name="qualityoutstanding" autofocus autocomplete="qualityoutstanding" />

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

                                        <x-jet-input :value="old('timelinesspoor')" class="{{ $errors->has('timelinesspoor') ? 'is-invalid' : '' }}" type="number" name="timelinesspoor" autofocus autocomplete="timelinesspoor" />

                                        <x-jet-input-error for="timelinesspoor"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Fair') }}" />

                                        <x-jet-input :value="old('timelinessfair')" class="{{ $errors->has('timelinessfair') ? 'is-invalid' : '' }}" type="number" name="timelinessfair" autofocus autocomplete="timelinessfair" />

                                        <x-jet-input-error for="timelinessfair"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Satisfactory') }}" />

                                        <x-jet-input :value="old('timelinesssatisfactory')" class="{{ $errors->has('timelinesssatisfactory') ? 'is-invalid' : '' }}" type="number" name="timelinesssatisfactory" autofocus autocomplete="timelinesssatisfactory" />

                                        <x-jet-input-error for="timelinesssatisfactory"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Very Satisfactory') }}" />

                                        <x-jet-input :value="old('timelinessvsatisfactory')" class="{{ $errors->has('timelinessvsatisfactory') ? 'is-invalid' : '' }}" type="number" name="timelinessvsatisfactory" autofocus autocomplete="timelinessvsatisfactory" />

                                        <x-jet-input-error for="timelinessvsatisfactory"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Outstanding') }}" />

                                        <x-jet-input :value="old('timelinessoutstanding')" class="{{ $errors->has('timelinessoutstanding') ? 'is-invalid' : '' }}" type="number" name="timelinessoutstanding" autofocus autocomplete="timelinessoutstanding" />

                                        <x-jet-input-error for="timelinessoutstanding"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Number of Hours') }}" />

                                        <x-jet-input :value="old('hours')" class="{{ $errors->has('hours') ? 'is-invalid' : '' }}" type="number" name="hours" autofocus autocomplete="hours" />

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

                                        <textarea class="form-control {{ $errors->has('documentdescription') ? 'is-invalid' : '' }}" name="documentdescription" cols="30" rows="5" autofocus autocomplete="documentdescription">{{ old('documentdescription') }}</textarea>

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