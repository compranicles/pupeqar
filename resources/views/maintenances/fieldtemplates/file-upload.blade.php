<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-3" id="upload-document">
    <div class="form-group">
        <label class="font-weight-bold">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        @if (isset($fieldInfo->h_r_i_s_form_id))
            @if ($fieldInfo->name == 'document')
                <span class="ml-3" role="alert">
                    Note: Certificate of Registration / Diploma / TOR. Attachments should be an image in JPEG format and less than 100kb in file size.
                </span>
            @endif
        @endif
        <input type="file"
            class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} mb-n1"
            name="{{ $fieldInfo->name }}"
            id="{{ $fieldInfo->name }}"
            {{ ($fieldInfo->required == 1) ? 'required' : '' }}
            accept="image/jpeg"
        >

        @error($fieldInfo->name)
            <span class='invalid-feedback' role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
