{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }}">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>

        <input type="text" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" value="{{ $value }}" class="form-control form-validation" 
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
        <small id="validation-{{ $fieldInfo->name }}" class="ml-2"></small>

    </div>
</div>