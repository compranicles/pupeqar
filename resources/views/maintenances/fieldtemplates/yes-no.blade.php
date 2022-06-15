
<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }}">
    <div class="form-group">
        <label class="{{ ($fieldInfo->required == 1) ? 'font-weight-bold' : '' }}">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        
        <div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }}">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}1" value="Yes" {{ ($value == 'Yes') ? 'checked' : '' }}
                {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
                <label class="form-check-label" for="{{ $fieldInfo->name }}1">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}2" value="No" {{ ($value == 'No') ? 'checked' : '' }}
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
                <label class="form-check-label" for="{{ $fieldInfo->name }}2">No</label>
            </div>
        </div>
        
    </div>
</div>


    