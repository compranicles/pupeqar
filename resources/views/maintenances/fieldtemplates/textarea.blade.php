
<div class="{{ $fieldInfo->size }} mb-3">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        @if ($fieldInfo->name == 'description')
        <button type="button" class="btn" data-bs-toggle="tooltip" data-bs-placement="right" title="For multiple description, you can hit <ENTER> key every description for auto-separation.">
        <i class="far fa-question-circle"></i>
        </button>
        @endif
        <textarea name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}"  class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control" {{ ($fieldInfo->required == 1) ? 'required' : '' }} @switch($fieldInfo->visibility) @case(2) {{ 'readonly' }} @break @case(3) {{ 'disabled' }} @break @case(2) {{ 'hidden' }} @break @default @endswitch>{{ (old($fieldInfo->name) == '') ? $value : old($fieldInfo->name) }}</textarea>
        @if ($fieldInfo->name == 'description')
        <span><small>{{ $fieldInfo->placeholder }}</small></span>
        @endif
        @error($fieldInfo->name)
            <span class='invalid-feedback' role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>
</div>

<script>
    $('textarea').keypress(function(e){
        if (e.keyCode == 13) {
            // alert($('textarea').val());
            $('textarea').val($('textarea').val() + ', ');
        }
    });
</script>