<div class="{{ $fieldInfo->size }}">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label>

        <input type="date" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" value="{{ $value }}" class="form-control" 
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

    </div>
</div>