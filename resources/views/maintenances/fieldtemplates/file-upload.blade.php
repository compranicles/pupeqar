<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-2" id="upload-document">
    <div class="form-group">
        <label class="font-weight-bold">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        @if (isset($fieldInfo->h_r_i_s_form_id))
            @if ($fieldInfo->name == 'document')
                <br>
                <span role="alert">
<<<<<<< Updated upstream
                    @if ($fieldInfo->h_r_i_s_form_id == 4 || $fieldInfo->h_r_i_s_form_id == 5)
                        Note: Attachments should be in <strong>JPEG/JPG, PNG, or PDF</strong> format and less than <strong>500kb</strong> in file size.
                    @else
                        Note: Attachment should be in <strong>JPEG/JPG, PNG, or PDF</strong> format and less than <strong>100kb</strong> in file size.
                    @endif
=======
                    Note: Attachments should be in <strong>JPEG/JPG, PNG, or PDF</strong> format and less than <strong>500kb</strong> in file size.
>>>>>>> Stashed changes
                </span>
                <br>
            @endif
        @endif
        <input type="file"
            class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} mt-2"
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
