{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-2">
    <div class="form-group">

        <label class="font-weight-bold form-label" for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label><span>{{ ($fieldInfo->dropdown_id != null) ? "^" : '' }}</span><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span><br>
            @if ($fieldInfo->name == 'name_of_student' || $fieldInfo->name == 'collaborator' ||
                $fieldInfo->name == 'authors_compilers' || $fieldInfo->name == 'editor_name' ||
                $fieldInfo->name == 'researchers' || $fieldInfo->name == 'article_author' || 
                $fieldInfo->name == 'name_of_contact_person')
                <span class="form-notes">
                    [Surname Suffix (if any), First Name M.I]
                </span>
            @endif
            @if (isset($fieldInfo->h_r_i_s_form_id))
                @if ($fieldInfo->h_r_i_s_form_id == 3 && $fieldInfo->name == 'position')
                <span class="form-notes">
                    Type your position in the organization.
                </span>
                @endif
                @if ($fieldInfo->h_r_i_s_form_id == 1 && $fieldInfo->name == 'honors')
                <span class="form-notes">
                    Academic/Non-Academic honors received by the employee.
                </span>
                @endif
            @endif
            @if($fieldInfo->dropdown_id != null)
            <span class="form-notes">
                ^
                @forelse($dropdown_options[$fieldInfo->name] as $index => $option)
                    @if(count($dropdown_options[$fieldInfo->name])-1 == $index)
                        {{ $option->name.'; If others, please specify.' }}
                    @else
                        {{ $option->name.'; ' }}
                    @endif
                @empty

                @endforelse
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
                <span class="form-notes">
                    <i class="bi bi-exclamation-circle-fill text-info"></i> {{ $fieldInfo->name == 'researchers' ? 'Include the researchers outside PUP. To include the researchers withtin the PUP system, you may tag them after saving this record.' : '' }}
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
