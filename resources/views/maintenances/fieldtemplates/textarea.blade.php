
<div class="{{ $fieldInfo->size }} mb-3">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        @if ($fieldInfo->name == 'description')
        <button type="button" class="btn btn-sm btn-link text-dark ml-n2" data-toggle="tooltip" data-placement="bottom" title="{{ $fieldInfo->placeholder }}">
            <i class="far fa-question-circle"></i>
        </button>
        @endif
        <textarea name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}"  class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control" placeholder="{{ $fieldInfo->placeholder }}" {{ ($fieldInfo->required == 1) ? 'required' : '' }} @switch($fieldInfo->visibility) @case(2) {{ 'readonly' }} @break @case(3) {{ 'disabled' }} @break @case(2) {{ 'hidden' }} @break @default @endswitch>{{ (old($fieldInfo->name) == '') ? $value : old($fieldInfo->name) }}</textarea>
        <span><small>{{ $fieldInfo->placeholder }}</small></span>
        @error($fieldInfo->name)
            <span class='invalid-feedback' role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>
</div>