{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} mb-2">
    <div class="form-group">
        <label class="font-weight-bold">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>

        <div class="input-group mb-2">
            <input type="decimal" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" value="{{ (old($fieldInfo->name) == '') ?  $value : old($fieldInfo->name) }}" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control form-validation" 
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
                            
                    @endswitch>
            <span class="input-group-text">%</span>
            @error($fieldInfo->name)
                <span class='invalid-feedback' role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>