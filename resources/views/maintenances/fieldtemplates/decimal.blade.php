{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }}">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label>

        <input type="number" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" pattern="(^[0-9]{0,2}$)|(^[0-9]{0,2}\.[0-9]{0,5}$)" value="{{ $value }}" class="form-control" 
            {{ ($fieldInfo->required == 1) ? 'required' : '' }} step="0.01" placeholder="{{ $fieldInfo->placeholder }}"
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
        $("#{{ $fieldInfo->name }}").on('blur' , function() {       
            var value = parseFloat($(this).val());
            $(this).val(value.toFixed(2));
        }); 
    </script>
@endpush