<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }}">
    <div class="form-group">
        <label for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label> <span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>

        <input type="date" name="{{ $fieldInfo->name }}" data-date="" id="{{ $fieldInfo->name }}" value="{{ ($value == null) ? date("Y-m-d") : $value }}" class="form-control form-validation date-modifier    " 
                {{ ($fieldInfo->required == 1) ? 'required' : '' }} data-date-format="L"
                @switch($fieldInfo->visibility)
                    @case(2)
                        {{ 'readonly' }}
                        @break
                    @case(3)
                        {{ 'disabled' }}
                        @break
                    @case(2)
                        {{ 'hidden' }}
                        @break
                    @default
                        
                @endswitch>

    </div>
</div>

@push('scripts')
    <script>
        $("#{{ $fieldInfo->name }}").on("change", function() {
            this.setAttribute(
                "data-date",
                moment(this.value, "YYYY-MM-DD")
                .format( this.getAttribute("data-date-format") )
            )
        }).trigger("change")
    </script>
@endpush