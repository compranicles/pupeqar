<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-3">
    <div class="form-group">
        <label for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label> <span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>

        <div class="input-group">

            <input type="text" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}"
                placeholder="mm/dd/yyyy" value="{{ (old($fieldInfo->name) == '') ?  ($value != '' ? date("m/d/Y", strtotime($value)) : '') : date("m/d/Y", strtotime(old($fieldInfo->name))) }}" 
                class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} datepicker 
                form-control form-validation p-3" autocomplete="off"
                {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
    
                    @error($fieldInfo->name)
                        <span class='invalid-feedback' role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
        </div>

    </div>
</div>  