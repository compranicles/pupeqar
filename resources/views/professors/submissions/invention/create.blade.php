<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('D.1 Faculty Invention, Innovation and Creative Works Commitment > Create') }}
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
                        <form action="{{ route('professor.submissions.invention.store') }}" method="POST">
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
                                        <x-jet-label value="{{ __('Title of the Invention, Innovation and, Creative Works') }}" />

                                        <x-jet-input :value="old('inventiontitle')" class="{{ $errors->has('inventiontitle') ? 'is-invalid' : '' }}" type="text" name="inventiontitle" autofocus autocomplete="inventiontitle" />

                                        <x-jet-input-error for="inventiontitle"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Classification') }}" />

                                        <select name="inventionclass" id="inventionclass" class="form-control custom-select {{ $errors->has('inventionclass') ? 'is-invalid' : '' }}" autofocus autocomplete="inventionclass">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($inventionclasses as $inventionclass)
                                            <option value="{{ $inventionclass->id }}" {{ ((old('inventionclass') == $inventionclass->id) ? 'selected' : '' )}}>{{ $inventionclass->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="inventionclass"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Collaborator/s') }}" />

                                        <textarea class="form-control {{ $errors->has('collaborator') ? 'is-invalid' : '' }}" name="collaborator" cols="30" rows="5" autofocus autocomplete="collaborator">{{ old('collaborator') }}</textarea>

                                        <x-jet-input-error for="collaborator"></x-jet-input-error>
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
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Nature of Inventions (IT Products, Equipments, Machinery, etc.)') }}" />

                                        <x-jet-input :value="old('inventionnature')" class="{{ $errors->has('inventionnature') ? 'is-invalid' : '' }}" type="text" name="inventionnature" autofocus autocomplete="inventionnature" />

                                        <x-jet-input-error for="inventionnature"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Status') }}" />

                                        <select name="inventionstatus" id="inventionstatus" class="form-control custom-select {{ $errors->has('inventionstatus') ? 'is-invalid' : '' }}" autofocus autocomplete="inventionstatus">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($inventionstatuses as $inventionstatus)
                                            <option value="{{ $inventionstatus->id }}" {{ ((old('inventionstatus') == $inventionstatus->id) ? 'selected' : '' )}}>{{ $inventionstatus->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="inventionstatus"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Funding Agency') }}" />

                                        <x-jet-input :value="old('fundingagency')" class="{{ $errors->has('fundingagency') ? 'is-invalid' : '' }}" type="text" name="fundingagency" autofocus autocomplete="fundingagency" />

                                        <x-jet-input-error for="fundingagency"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Type of Funding') }}" />

                                        <select name="fundingtype" id="fundingtype" class="form-control custom-select {{ $errors->has('fundingtype') ? 'is-invalid' : '' }}" autofocus autocomplete="fundingtype">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($fundingtypes as $fundingtype)
                                            <option value="{{ $fundingtype->id }}" {{ ((old('fundingtype') == $fundingtype->id) ? 'selected' : '' )}}>{{ $fundingtype->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="fundingtype"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Amount of Fund') }}" />

                                        <x-jet-input :value="old('fundingamount')" class="{{ $errors->has('fundingamount') ? 'is-invalid' : '' }}" type="text" name="fundingamount" autofocus autocomplete="fundingamount" />

                                        <x-jet-input-error for="fundingamount"></x-jet-input-error>
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