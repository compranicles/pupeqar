<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('A. Ongoing Advanced/Professional Study Form') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('professor.submissions.ongoingadvanced.store') }}" method="POST">
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
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Degree/Program') }}" />

                                        <x-jet-input :value="old('degree')" class="{{ $errors->has('degree') ? 'is-invalid' : '' }}" type="text" name="degree" autofocus autocomplete="degree" />

                                        <x-jet-input-error for="degree"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 id=textHome style="color: maroon"><b>School</b></h5>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Name of School') }}" />

                                        <x-jet-input :value="old('school')" class="{{ $errors->has('school') ? 'is-invalid' : '' }}" type="text" name="school" autofocus autocomplete="school" />

                                        <x-jet-input-error for="school"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-5">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Program Accreditation Level/World Ranking/COE or COD') }}" />

                                        <select name="accrelevel" id="accrelevel" class="form-control custom-select {{ $errors->has('accrelevel') ? 'is-invalid' : '' }}" autofocus autocomplete="accrelevel">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($accrelevels as $accrelevel)
                                            <option value="{{ $accrelevel->id }}" {{ ((old('accrelevel') == $accrelevel->id) ? 'selected' : '' )}}>{{ $accrelevel->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="accrelevel"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 id=textHome style="color: maroon"><b>Means of Educational Support</b></h5>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Type of Support') }}" />

                                        <select name="supporttype" id="supporttype" class="form-control custom-select {{ $errors->has('supporttype') ? 'is-invalid' : '' }}" autofocus autocomplete="supporttype">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($supporttypes as $supporttype)
                                            <option value="{{ $supporttype->id }}" {{ ((old('supporttype') == $supporttype->id) ? 'selected' : '' )}}>{{ $supporttype->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="supporttype"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Name of Sponsor/Agency/Organization') }}" />

                                        <x-jet-input :value="old('sponsor')" class="{{ $errors->has('sponsor') ? 'is-invalid' : '' }}" type="text" name="sponsor" autofocus autocomplete="sponsor" />

                                        <x-jet-input-error for="sponsor"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Amount') }}" />

                                        <x-jet-input :value="old('amount')" class="{{ $errors->has('amount') ? 'is-invalid' : '' }}" type="text" name="amount" autofocus autocomplete="amount" />

                                        <x-jet-input-error for="amount"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <h5 id=textHome style="color: maroon"><b>Duration</b></h5>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('From ') }}" />

                                        <x-jet-input :value="old('date_started')" class="{{ $errors->has('date_started') ? 'is-invalid' : '' }}" type="text" id="date-start" name="date_started" autofocus autocomplete="date_started" />

                                        <x-jet-input-error for="date_started"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4 ">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('To') }}" />

                                        <x-jet-input :value="old('date_ended')" class="{{ $errors->has('date_ended') ? 'is-invalid' : '' }}" type="text" id="date-end" name="date_ended" autofocus autocomplete="date_ended" />

                                        <x-jet-input-error for="date_ended"></x-jet-input-error>

                                    </div>
                                    <div class="form-check">
                                        
                                        <x-jet-checkbox :value="old('present')" class="{{ $errors->has('present') ? 'is-invalid' : '' }}" id="present" name="present" autofocus autocomplete="present" />
                                            
                                        <x-jet-label value="{{ __('Present') }}" class="form-check-label"/>

                                        <x-jet-input-error for="present"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Status') }}" />

                                        <select name="studystatus" id="studystatus" class="form-control custom-select {{ $errors->has('studystatus') ? 'is-invalid' : '' }}" autofocus autocomplete="studystatus">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($studystatuses as $studystatus)
                                            <option value="{{ $studystatus->id }}" {{ ((old('studystatus') == $studystatus->id) ? 'selected' : '' )}}>{{ $studystatus->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="studystatus"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Number of Units Earned') }}" />

                                        <x-jet-input :value="old('unitsearned')" class="{{ $errors->has('unitsearned') ? 'is-invalid' : '' }}" type="text" name="unitsearned" autofocus autocomplete="unitsearned" />

                                        <x-jet-input-error for="unitsearned"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Number of Units Currently Enrolled') }}" />

                                        <x-jet-input :value="old('unitsenrolled')" class="{{ $errors->has('unitsenrolled') ? 'is-invalid' : '' }}" type="text" name="unitsenrolled" autofocus autocomplete="unitsenrolled" />

                                        <x-jet-input-error for="unitsenrolled"></x-jet-input-error>
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
                element: document.getElementById('date-start'),
                elementEnd: document.getElementById('date-end'),
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
            var toinput = document.getElementById('date-end');
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