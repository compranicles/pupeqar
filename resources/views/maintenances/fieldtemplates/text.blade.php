{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-3">
    <div class="form-group">
        <label class="font-weight-bold form-label" for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
                @if ($fieldInfo->name == 'name_of_student' || $fieldInfo->name == 'collaborator' ||
                    $fieldInfo->name == 'authors_compilers' || $fieldInfo->name == 'editor_name' ||
                    $fieldInfo->name == 'researchers' || $fieldInfo->name == 'article_author' || 
                    $fieldInfo->name == 'name_of_contact_person')
                    <span id="" role="alert" class="ml-3">
                        [Surname Suffix (if any), First Name M.I]
                    </span>
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
                    @case(4)
                        {{ 'hidden' }}
                        @break
                    @default
                        
                @endswitch>
                @if ($fieldInfo->name == 'researchers')
                    <span id="" role="alert">
                        <i class="bi bi-exclamation-circle-fill text-info"></i> {{ $fieldInfo->name == 'researchers' ? 'Include the researchers outside PUP. To include the researchers within PUP, add them after you save this research to share them info.' : '' }}
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
<!-- For words separated by (',') -->
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
    $("#utilization").selectize({
        delimiter: "/",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });

    $("#editor_profession").selectize({
        delimiter: ",",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });
    $("#beneficiaries").selectize({
        delimiter: ",",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });
    $("#name_of_adoptor").selectize({
        delimiter: "/",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });
    $("#nature_of_business_enterprise").selectize({
        delimiter: "/",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });
    $("#journal_publisher").selectize({
        delimiter: "/",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });
    $("#editor").selectize({
        delimiter: "/",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });
    $("#organization").selectize({
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
<!-- For people names separated by ('/') -->
<script>
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

    $("#researchers").selectize({
        delimiter: "/",
        persist: false,
        create: function (input) {
            return {
            value: input,
            text: input,
            };
        },
    });
    $("#article_author").selectize({
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