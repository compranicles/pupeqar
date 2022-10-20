<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-2" id="upload-document">
    <div class="form-group">
        <label class="font-weight-bold">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        {{-- <br>
        <span class="form-notes">
            Note: Finish the uploading of files before saving.
        </span> --}}
        <input type="file"
            class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} filepond mb-n1"
            name="{{ $fieldInfo->name }}[]"
            id="{{ $fieldInfo->name }}"
            multiple
            accept="application/pdf, image/*"
            data-max-file-size="10MB"
            data-max-files="5"
            {{ ($fieldInfo->required == 1) ? 'required' : '' }}
            >

            @error($fieldInfo->name)
                <span class='invalid-feedback' role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        <p class="mt-1"><small>Maximum size per file: 10MB. Maximum number of files: 5.</small></p>
        <p class="mt-n4"><small>Accepts PDF, JPEG, and PNG file formats.</small></p>

    </div>
</div>

@push('scripts')
    <script>
    
        // var url = "{{ url('upload') }}";
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
        const pondDocument = FilePond.create(document.querySelector('input[name="{{ $fieldInfo->name }}[]"]'), {
            storeAsFile: true,
        });
        // pondDocument.setOptions({
        //     acceptedFileTypes: ['application/pdf', 'image/*'],   ///jpeg', 'image/png', 'image/x-png', 'image/pjpeg', 'image/jpg', 'image/jfif', 'image/pjp'
        //     // server: {
                
        //     //     timeout: 20000,
        //     //     process: {
        //     //         // url: url,
        //     //         headers: {
        //     //             'X-CSRF-TOKEN': '{{ csrf_token() }}',
        //     //         }
        //     //     },
        //     // }
          
        // });
    </script>
    <!-- <script>
        $( "#document" ).on( "input", function() {
            const inputElement = document.querySelector('input[type="file"]');
            const pond = FilePond.create(inputElement, {
                onaddfilestart: (file) => { isLoadingCheck(); },
                onprocessfile: (files) => { isLoadingCheck(); }
            });
    
            function isLoadingCheck(){
                var isLoading = pond.getFiles().filter(x=>x.status !== 5).length !== 0;
                if(isLoading) {
                    $('#submit').attr("disabled", "disabled");
                } else {
                    $('#submit').removeAttr("disabled");
                }
            }
        });
    </script> -->
@endpush
