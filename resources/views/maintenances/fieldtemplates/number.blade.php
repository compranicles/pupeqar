{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} mb-2">
    <div class="form-group">
        <label class="font-weight-bold" for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        @if (isset($fieldInfo->h_r_i_s_form_id))
            @if ($fieldInfo->h_r_i_s_form_id == 1 && $fieldInfo->name == 'from')
            <span class="form-notes">
                Inclusive dates of attendance (ex. 1995-1999).
            </span>
            @endif
        @endif
        <input type="number" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" value="{{ (old($fieldInfo->name) == '') ?  $value : old($fieldInfo->name) }}" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control form-validation" 
                placeholder="{{ $fieldInfo->placeholder }}" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
                @switch($fieldInfo->visibility)
                    @case(2)
                        {{ 'readonly' }}
                        @break
                    @case(3)
                        {{ 'disabled' }}
                        @break
                    @case(4)
                        {{ 'hidden' }}
                        @break
                    @default
                        
                @endswitch >

                @error($fieldInfo->name)
                    <span class='invalid-feedback' role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

    </div>
</div>