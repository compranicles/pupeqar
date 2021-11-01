{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }}">
    <div class="form-group">
        <label for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label>

        <select name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" class="form-control custom-select" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
            <option value="" selected disabled>Choose...</option>
            
        </select>
    </div>
</div>


@push('scripts')
    <script>
        $('#{{ $fieldInfo->name }}').ready(function (){
            $.get("{{ route('dropdowns.options', $fieldInfo->dropdown_id) }}", function (data){
                data.forEach(function (item){
                    $("#{{ $fieldInfo->name }}").append(new Option(item.name, item.id));
                });
                var value = "{{ $value }}";
                if (value != ''){
                    document.getElementById("{{ $fieldInfo->name }}").value = "{{ $value }}";
                }
            });
        });
    </script>
@endpush