<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-2" id="upload-document">
    <div class="form-group">
        <label class="font-weight-bold">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        @if (isset($fieldInfo->h_r_i_s_form_id))
            @if ($fieldInfo->name == 'document')
                <span class="ml-3" role="alert">
                    @if ($fieldInfo->h_r_i_s_form_id == 4 || $fieldInfo->h_r_i_s_form_id == 5)
                        Note: Certificate of Registration / Diploma / TOR. Attachments should be in PDF or JPEG format and less than 500kb in file size.
                    @else
                        Note: Certificate of Registration / Diploma / TOR. Attachments should be in JPEG format and less than 100kb in file size.
                    @endif
                </span>
            @endif
        @endif
        <input type="file"
            class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} mb-n1"
            name="{{ $fieldInfo->name }}"
            id="{{ $fieldInfo->name }}"
            {{ ($fieldInfo->required == 1) ? 'required' : '' }}
            accept="image/jpeg, application/pdf, image/png"
        >

        @error($fieldInfo->name)
            <span class='invalid-feedback' role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
