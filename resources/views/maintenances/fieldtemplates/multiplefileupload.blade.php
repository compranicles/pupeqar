<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-3" id="upload-document">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>

        <input type="file" 
            class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} filepond mb-n1"
            name="{{ $fieldInfo->name }}[]"
            id="{{ $fieldInfo->name }}"
            multiple
            data-max-file-size="5MB"
            data-max-files="10"
            {{ ($fieldInfo->required == 1) ? 'required' : '' }}
            >

            @error($fieldInfo->name)
                <span class='invalid-feedback' role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        <p class="mt-1"><small>Maximum size per file: 5MB. Maximum number of files: 10.</small></p>
        <p class="mt-n4"><small>Accepts PDF, JPEG, and PNG file formats.</small></p>

    </div>
</div>

@push('scripts')
    <script>
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
        const pondDocument = FilePond.create(document.querySelector('input[name="{{ $fieldInfo->name }}[]"]'));
        pondDocument.setOptions({
            acceptedFileTypes: ['application/pdf', 'image/jpeg', 'image/png'],
            
            server: {
                process: {
                    url: "/upload",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    }
                },
            }
        });
    </script>
@endpush