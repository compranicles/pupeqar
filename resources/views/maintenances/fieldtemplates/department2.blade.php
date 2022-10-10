<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-2">
    <div class="form-group">
        <label class="font-weight-bold" for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        <br>
        <span class="form-notes">
            Select <strong>N/A</strong> if the accomplishment is a <strong>college-wide</strong> (filled-in by Dean/Director).
        </span>
        <select name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control custom-select form-validation" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
                @endswitch
            >
    
            <option value="" selected disabled>Choose...</option>
        </select>
       
        @error($fieldInfo->name)
            <span class='invalid-feedback' role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
