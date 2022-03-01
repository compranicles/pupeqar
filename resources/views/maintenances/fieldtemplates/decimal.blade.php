{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} mb-3">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label>
        <div class="input-group mb-3">
            <div class="input-group-prepend">
                <select class="custom-select" name="currency_{{ $fieldInfo->name }}" id="currency_select_{{ $fieldInfo->name }}">
                  <option disabled selected>Choose...</option>
                </select>
            </div>
            <input type="decimal" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" value="{{ (old($fieldInfo->name) == '') ?  number_format(($value == null) ? 0.00 : $value, 2, '.', ',') : old($fieldInfo->name) }}" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control form-validation" 
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

                @error($fieldInfo->name)
                    <span class='invalid-feedback' role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
        </div>
        

    </div>
</div>

@push('scripts')
    <script>
        $('#currency_select_{{ $fieldInfo->name }}').ready(function (){
            $.get("{{ route('currencies.list') }}", function (data){
                data.forEach(function (item){
                    $("#currency_select_{{ $fieldInfo->name }}").append(new Option(item.code, item.id));
                });
                var value = "{{ $currency }}";
                if (value == ''){
                    $("#currency_select_{{ $fieldInfo->name }}").val(74);
                }else{
                    $("#currency_select_{{ $fieldInfo->name }}").val(value);
                }
            });
        });
    </script>
    <script>
        $("#{{ $fieldInfo->name }}").on('blur' , function() {       
            var value = parseFloat($(this).val());
            var actual = number_format(value, 2, '.', ',');
            $(this).val(actual);
            
        }); 

        function number_format(number, decimals, dec_point, thousands_sep) {
            var n = !isFinite(+number) ? 0 : +number, 
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return Math.round(n * k) / k;
                },
                s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }
    </script>
@endpush