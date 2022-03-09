
<div class="{{ $fieldInfo->size }} mb-3">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>

        <textarea name="{{ $fieldInfo->name }}" placeholder="Add description or select/edit from suggestions" id="{{ $fieldInfo->name }}"  class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control" {{ ($fieldInfo->required == 1) ? 'required' : '' }} @switch($fieldInfo->visibility) @case(2) {{ 'readonly' }} @break @case(3) {{ 'disabled' }} @break @case(2) {{ 'hidden' }} @break @default @endswitch>{{ (old($fieldInfo->name) == '') ? $value : old($fieldInfo->name) }}</textarea>
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

<script src="{{ asset('dist/selectize.min.js') }}"></script>

<script>
    $('#description').keypress(function(e){
        if (e.keyCode == 13) {
            // alert($('textarea').val());
            $('textarea').val($('textarea').val() + ', ');
        }
    });
</script>
<script>
    $("#description").selectize({
        plugins: ["restore_on_backspace"],
        delimiter: ",",
        persist: true,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });
</script>