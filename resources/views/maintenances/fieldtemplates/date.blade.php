
<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-2">
    <div class="form-group">
        <label class="font-weight-bold" for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label> <span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        @if (isset($fieldInfo->h_r_i_s_form_id))
            @if ($fieldInfo->h_r_i_s_form_id == 2 && $fieldInfo->name == 'from')
            <span class="form-notes">
                Inclusive date awarded/conferred.
            </span>
            @endif
            @if ($fieldInfo->h_r_i_s_form_id == 3 && $fieldInfo->name == 'to')
            <input type="checkbox" name="current_member" value="1" id="current-member" class="ml-3">
            <label for="current-member">Member until present</label>
            @endif
        @endif
        <input type="text" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}"
            placeholder="mm/dd/yyyy" pattern="[0-9]{1,2}/[0-9]{1,2}/[0-9]{4}"
            value="{{ (old($fieldInfo->name) == '') ? ($value != null ? ($value == 'present' ? 'present' : date("m/d/Y", strtotime($value))) : date("m/d/Y", strtotime($value))) : date("m/d/Y", strtotime(old($fieldInfo->name))) }}"
            class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} datepicker
            form-control form-validation p-3" autocomplete="off"
            {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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

                @error($fieldInfo->name)
                    <span class='invalid-feedback' role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

    </div>
</div>
