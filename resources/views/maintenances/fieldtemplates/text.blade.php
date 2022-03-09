{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-3">
    <div class="form-group">
        <label for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        <!-- @if ($fieldInfo->name == 'keywords')
        <button type="button" class="btn" data-bs-toggle="tooltip" data-bs-placement="right" title="Please use comma (,) to separate the keywords.">
        <i class="far fa-question-circle"></i>
        </button>
        @endif
        @if ($fieldInfo->name == 'name_of_student')
        <button type="button" class="btn" data-bs-toggle="tooltip" data-bs-placement="right" title="For multiple input of students, please use forward slash (/) to separate the names.">
        <i class="far fa-question-circle"></i>
        </button>
        @endif -->
        <input type="text" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" value="{{ (old($fieldInfo->name) == '') ?  $value : old($fieldInfo->name) }}" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control form-validation" 
                placeholder="{{ $fieldInfo->placeholder }}" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
                        
                @endswitch>
                @if ($fieldInfo->name == 'utilization')
                    <span role="alert">
                        <small>Required if the classification is <em>invention</em>.</small>
                    </span>
                @endif
                @if ($fieldInfo->label == 'Please specify')
                    <span id="" role="alert">
                        <small>Required if the previous selection is <em>others</em>.</small>
                    </span>
                @endif
                @if ($fieldInfo->label == 'Name of Collaborators' || $fieldInfo->label == 'Authors/Compilers' || $fieldInfo->label == 'Name of Editor/Referee')
                    <span id="" role="alert">
                        <small>Surname, First Name M.I.</small>
                    </span>
                @endif

                @error($fieldInfo->name)
                    <span class='invalid-feedback' role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
    </div>
</div>
@push('scripts')
<script src="{{ asset('dist/selectize.min.js') }}"></script>
<script>
    $("#keywords").selectize({
        delimiter: ",",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });
</script>
<script>
    $("#utilization").selectize({
        delimiter: ",",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });

    $("#name_of_student").selectize({
        delimiter: "/",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });

    $("#collaborator").selectize({
        delimiter: "/",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });

    $("#authors_compilers").selectize({
        delimiter: "/",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });

    $("#editor_name").selectize({
        delimiter: "/",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });
</script>
@endpush