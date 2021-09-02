<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('C.2. Research Publication > Create') }}
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
                        <form action="{{ route('professor.submissions.researchpublication.store') }}" method="POST">
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
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Classification') }}" />

                                        <select name="researchclass" id="researchclass" class="form-control custom-select {{ $errors->has('researchclass') ? 'is-invalid' : '' }}" autofocus autocomplete="researchclass">
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach($researchclasses as $researchclass)
                                            <option value="{{ $researchclass->id }}" {{ ((old('researchclass') == $researchclass->id) ? 'selected' : '' )}}>{{ $researchclass->name }}</option>    
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
                                            <option value="{{ $researchcategory->id }}" {{ ((old('researchcategory') == $researchcategory->id) ? 'selected' : '' )}}>{{ $researchcategory->name }}</option>    
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
                                            <option value="{{ $researchagenda->id }}" {{ ((old('researchagenda') == $researchagenda->id) ? 'selected' : '' )}}>{{ $researchagenda->name }}</option>    
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

                                        <textarea class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" name="title" cols="30" rows="5" autofocus autocomplete="title">{{ old('title') }}</textarea>

                                        <x-jet-input-error for="title"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Researcher/s (Surname, First Name M.I.)') }}" />

                                        <textarea class="form-control {{ $errors->has('researchers') ? 'is-invalid' : '' }}" name="researchers" cols="30" rows="5" autofocus autocomplete="researchers">{{ old('researchers') }}</textarea>


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
                                            <option value="{{ $researchinvolve->id }}" {{ ((old('researchinvolve') == $researchinvolve->id) ? 'selected' : '' )}}>{{ $researchinvolve->name }}</option>    
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
                                            <option value="{{ $researchtype->id }}" {{ ((old('researchtype') == $researchtype->id) ? 'selected' : '' )}}>{{ $researchtype->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="researchtype"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Keywords (at least five (5) keywords)') }}" />

                                        <textarea class="form-control {{ $errors->has('keywords') ? 'is-invalid' : '' }}" name="keywords" cols="30" rows="5" autofocus autocomplete="title">{{ old('keywords') }}</textarea>

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
                                            <option value="{{ $fundingtype->id }}" {{ ((old('fundingtype') == $fundingtype->id) ? 'selected' : '' )}}>{{ $fundingtype->name }}</option>    
                                            @endforeach
                                        </select>

                                        <x-jet-input-error for="fundingtype"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Amount of Funding') }}" />

                                        <x-jet-input :value="old('fundingamount')" class="{{ $errors->has('fundingamount') ? 'is-invalid' : '' }}" type="text" id="fundingamount" name="fundingamount" autofocus autocomplete="fundingamount" />

                                        <x-jet-input-error for="fundingamount"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Funding Agency') }}" />

                                        <x-jet-input :value="old('fundingagency')" class="{{ $errors->has('fundingagency') ? 'is-invalid' : '' }}" type="text" id="fundingagency" name="fundingagency" autofocus autocomplete="fundingagency" />

                                        <x-jet-input-error for="fundingagency"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Actual Date Started') }}" />

                                        <x-jet-input :value="old('datestarted')" class="{{ $errors->has('datestarted') ? 'is-invalid' : '' }}" type="text" id="datestarted " name="datestarted" autofocus autocomplete="datestarted " />

                                        <x-jet-input-error for="datestarted"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Target Date of Completion') }}" />

                                        <x-jet-input :value="old('datetargeted')" class="{{ $errors->has('datetargeted') ? 'is-invalid' : '' }}" type="text" id="datetargeted" name="datetargeted" autofocus autocomplete="datetargeted" />

                                        <x-jet-input-error for="datetargeted"></x-jet-input-error>

                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Date Completed') }}" />

                                        <x-jet-input :value="old('datecompleted')" class="{{ $errors->has('datecompleted') ? 'is-invalid' : '' }}" type="text" id="datecompleted" name="datecompleted" autofocus autocomplete="datecompleted" />

                                        <x-jet-input-error for="datecompleted"></x-jet-input-error>

                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Journal Name') }}" />

                                        <x-jet-input :value="old('journalname')" class="{{ $errors->has('journalname') ? 'is-invalid' : '' }}" type="text" id="journalname" name="journalname" autofocus autocomplete="journalname" />

                                        <x-jet-input-error for="journalname"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-1">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Page No.') }}" />

                                        <x-jet-input :value="old('page')" class="{{ $errors->has('page') ? 'is-invalid' : '' }}" type="text" id="page" name="page" autofocus autocomplete="page" />

                                        <x-jet-input-error for="page"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-2">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Volume No.') }}" />

                                        <x-jet-input :value="old('volume')" class="{{ $errors->has('volume') ? 'is-invalid' : '' }}" type="text" id="volume" name="volume" autofocus autocomplete="volume" />

                                        <x-jet-input-error for="volume"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-1">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Issue No.') }}" />

                                        <x-jet-input :value="old('issue')" class="{{ $errors->has('issue') ? 'is-invalid' : '' }}" type="text" id="issue" name="issue" autofocus autocomplete="issue" />

                                        <x-jet-input-error for="issue"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Indexing Platform') }}" />

                                        <x-jet-input :value="old('indexingplatform')" class="{{ $errors->has('indexingplatform') ? 'is-invalid' : '' }}" type="text" id="indexingplatform" name="indexingplatform" autofocus autocomplete="indexingplatform" />

                                        <x-jet-input-error for="indexingplatform"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Date Published') }}" />

                                        <x-jet-input :value="old('datepublished')" class="{{ $errors->has('datepublished') ? 'is-invalid' : '' }}" type="text" id="datepublished" name="datepublished" autofocus autocomplete="datepublished" />

                                        <x-jet-input-error for="datepublished"></x-jet-input-error>

                                    </div>
                                </div>
                                <div class="col-lg-9">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Publisher') }}" />

                                        <x-jet-input :value="old('publisher')" class="{{ $errors->has('publisher') ? 'is-invalid' : '' }}" type="text" id="publisher" name="publisher" autofocus autocomplete="publisher" />

                                        <x-jet-input-error for="publisher"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Editor') }}" />

                                        <x-jet-input :value="old('editor')" class="{{ $errors->has('editor') ? 'is-invalid' : '' }}" type="text" id="editor" name="editor" autofocus autocomplete="editor" />

                                        <x-jet-input-error for="editor"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('ISSN/ISBN') }}" />

                                        <x-jet-input :value="old('isbn')" class="{{ $errors->has('isbn') ? 'is-invalid' : '' }}" type="text" id="isbn" name="isbn" autofocus autocomplete="isbn" />

                                        <x-jet-input-error for="isbn"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Level') }}" />

                                        <x-jet-input :value="old('level')" class="{{ $errors->has('level') ? 'is-invalid' : '' }}" type="text" id="level" name="level" autofocus autocomplete="level" />

                                        <x-jet-input-error for="level"></x-jet-input-error>
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
                element: document.getElementById('datepublished'),
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
    @endpush
</x-app-layout>