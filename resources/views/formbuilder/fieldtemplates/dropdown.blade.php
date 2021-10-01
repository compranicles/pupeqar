{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }}">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label>

        <select name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" class="form-control custom-select" {{ $fieldInfo->required }}>
            <option value="" selected>Choose...</option>
            
        </select>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function (){
            $.get("{{ route('dropdown.options', $fieldInfo->dropdown_id) }}", function (data){
                data.forEach(function (item){
                    $("#{{ $fieldInfo->name }}").append(new Option(item.label, item.label));
                });
                document.getElementById("{{ $fieldInfo->name }}").value = "{{ $value }}";
            });
        });
    </script>
@endpush