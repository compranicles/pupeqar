<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-3">
    <label for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>

    <select name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" class="form-control custom-select form-validation" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
            @endswitch
        >
        <option value="" selected disabled>Choose...</option>
    </select>
</div>