{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-3">
    <div class="form-group">
        <label for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        @if ($fieldInfo->name == 'keywords')
        <button type="button" class="btn" data-bs-toggle="tooltip" data-bs-placement="right" title="Please use comma (,) to separate the keywords.">
        <i class="far fa-question-circle"></i>
        </button>
        @endif
        @if ($fieldInfo->name == 'name_of_student')
        <button type="button" class="btn" data-bs-toggle="tooltip" data-bs-placement="right" title="For multiple input of students, please use forward slash (/) to separate the names.">
        <i class="far fa-question-circle"></i>
        </button>
        @endif
        <input type="text" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" value="{{ (old($fieldInfo->name) == '') ?  $value : old($fieldInfo->name) }}" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control form-validation" 
                placeholder="{{ $fieldInfo->placeholder }}" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
                @switch($fieldInfo->visibility)
                    @case(2)
                        {{ 'readonly' }}
                        @break
                    @case(3)
                        {{ 'disabled' }}
                        @break
                    @case(2)
                        {{ 'hidden' }}
                        @break
                    @default
                        
                @endswitch>
                @if ($fieldInfo->name == 'keywords')
                    <span id="validation-keywords" role="alert" style="color: red;">
                        <strong></strong>
                    </span>
                @endif

                @if ($fieldInfo->name == 'funding_agency')
                    <span id="" role="alert">
                        <small>Required input for externally-funded.</small>
                    </span>
                @endif
                @if ($fieldInfo->label == 'Please specify')
                    <span id="" role="alert">
                        <small>Required if the previous selection is <em>others</em>.</small>
                    </span>
                @endif

                @error($fieldInfo->name)
                    <span class='invalid-feedback' role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
    </div>
</div>