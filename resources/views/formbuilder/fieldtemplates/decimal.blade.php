{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }}">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label>

        <input type="number" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" pattern="(^[0-9]{0,2}$)|(^[0-9]{0,2}\.[0-9]{0,5}$)" value="{{ $value }}" class="form-control" {{ $fieldInfo->required }} step="0.01" placeholder="0.00">

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