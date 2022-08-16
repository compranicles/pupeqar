{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-2">
    <div class="form-group">
        <label class="font-weight-bold" for="{{ $fieldInfo->name }}" >{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span><br>
        @if (isset($fieldInfo->h_r_i_s_form_id))
            @if ($fieldInfo->h_r_i_s_form_id == 3 && $fieldInfo->name == 'level')
            <span class="form-notes">
                Select level of the organization.
            </span>
            @endif
            @if ($fieldInfo->h_r_i_s_form_id == 2 && $fieldInfo->name == 'level')
            <span class="form-notes">
                Select level of achievement/award.
            </span>
            @endif
        @endif
        @if ($fieldInfo->name == 'classification_of_person')
        <span class="form-notes">
            Please select your designation.
        </span>
        @endif
        <select name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control custom-select form-validation {{ $fieldInfo->name }}" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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

            <option value="" selected disabled>Choose...</option>
            @isset($dropdown_options[$fieldInfo->name])
                @foreach($dropdown_options[$fieldInfo->name] as $option)
                    <option value="{{ $option->id }}" {{ (old($fieldInfo->name, $value ) == $option->id) ? 'selected' : '' }}>{{ $option->name }}</option>
                @endforeach
            @endisset
            @if (isset($fieldInfo->h_r_i_s_form_id))
            @if ($fieldInfo->h_r_i_s_form_id == 4 && $fieldInfo->name == 'fund_source')
                <span class="ml-3" role="alert">
                    <option value="0" {{ (old($fieldInfo->name, $value ) == 0) ? 'selected' : '' }}>Not a Paid Seminar/Training</option>
                </span>
            @endif
            @if ($fieldInfo->h_r_i_s_form_id == 1 && $fieldInfo->name != 'level')
                <span class="ml-3" role="alert">
                    <option value="0" {{ (old($fieldInfo->name, $value ) == 0) ? 'selected' : '' }}>Not Applicable</option>
                </span>
            @endif
        @endif

        </select>

        @if (isset($fieldInfo->research_form_id))
            @if ($fieldInfo->name == 'status' && $fieldInfo->research_form_id == 1)
            <span class="form-notes">
                <p>Please select <strong>new commitment</strong> or <strong>ongoing</strong> before proceeding for completion, etc.</p>
            </span>
            @endif
        @endif
        @if ($fieldInfo->name == 'classification_of_person')
        <span class="form-notes">
            <p>Chair/Chief/Dean/Director will encode for <strong>student</strong> mobility.</p>
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
    <script>
        @if(!isset($dropdown_options[$fieldInfo->name]))
        setTimeout(function (){

            $.ajax('{{ route('dropdowns.options', $fieldInfo->dropdown_id) }}',   // request url
            {
                success: function (data, status, xhr) {    // success callback function
                    data.forEach(function (item){
                        $("#{{ $fieldInfo->name }}").append(new Option(item.name, item.id)).change();
                    });
                    var value = "{{ $value }}";
                    if (value != ''){
                        $("#{{ $fieldInfo->name }}").val("{{ $value }}");
                    }
                    <?php if (old($fieldInfo->name) != '') { ?>
                        $("#{{ $fieldInfo->name }}").val("{{ old($fieldInfo->name) }}");
                    <?php } ?>
                }
            });
        }, Math.floor(Math.random() * (2500 - 1) + 1));
        @endif

    </script>
@endpush
