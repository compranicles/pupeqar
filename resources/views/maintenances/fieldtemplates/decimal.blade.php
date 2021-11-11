{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }}">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label><?php if ($fieldInfo->required == 1) { echo "<span style='color: red'> *</span>"; } ?>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <select class="custom-select" name="currency" id="currency_select">
                  <option disabled selected>Choose...</option>
                </select>
            </div>
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
</div>

@push('scripts')
    <script>
        $('#currency_select').ready(function (){
            $.get("{{ route('currencies.list') }}", function (data){
                data.forEach(function (item){
                    $("#currency_select").append(new Option(item.code, item.id));
                });
                var value = "{{ $currency }}";
                if (value == ''){
                    $("#currency_select").val(74);
                }else{
                    $("#currency_select").val(value);
                }
            });
        });
    </script>
    <script>
        $("#{{ $fieldInfo->name }}").on('blur' , function() {       
            var value = parseFloat($(this).val());
            $(this).val(value.toFixed(2));
        }); 
    </script>
@endpush