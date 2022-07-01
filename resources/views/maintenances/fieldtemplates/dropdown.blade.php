{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-3">
    <div class="form-group">
        <label class="font-weight-bold" for="{{ $fieldInfo->name }}" >{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>

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

        </select>

        @if (isset($fieldInfo->research_form_id))
            @if ($fieldInfo->name == 'status' && $fieldInfo->research_form_id == 1)
            <span>
                <p>Please select <strong>new commitment</strong> or <strong>ongoing</strong> before proceeding for completion, etc.</p>
            </span>
            @endif
        @endif
        @if ($fieldInfo->name == 'classification_of_person')
        <span>
            <p>Please select your designation. <br>Chair/Dean/Director will encode for <strong>student</strong> mobility.</p>
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
    </script>
@endpush
