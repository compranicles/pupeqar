<div class="{{ $fieldInfo->size }}">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label>

        <input type="date" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" value="{{ $value }}" class="form-control" {{ $fieldInfo->required }}>

    </div>
</div>