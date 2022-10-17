
<div class="{{ $fieldInfo->size }} mb-2">
    <div class="form-group">
        <label class="font-weight-bold">{{ $fieldInfo->label }}</label>
        <span style='color:red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        <br>
        @if ($fieldInfo->name == 'description' && $fieldInfo->placeholder != '')
            <span class="form-notes">
                Recommended file/s to upload (any of the ff.): {{ $fieldInfo->placeholder }}
            </span>
        @endif
        <input type="text" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" 
            class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control" 
            {{ ($fieldInfo->required == 1) ? 'required' : '' }} 
            @switch($fieldInfo->visibility) 
                @case(2) {{ 'readonly' }} 
                    @break 
                @case(3) {{ 'disabled' }} 
                    @break 
                @case(4) {{ 'hidden' }} 
                    @break 
                @default
             @endswitch 
             @if (isset($fieldInfo->h_r_i_s_form_id)) 
                placeholder="Certificate of Registration / Diploma / TOR"
             @endif>
             {{ (old($fieldInfo->name) == '') ? $value : old($fieldInfo->name) }}
        </textarea>
        @error($fieldInfo->name)
            <span class='invalid-feedback' role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

