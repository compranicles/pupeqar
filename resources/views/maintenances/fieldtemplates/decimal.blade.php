{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} mb-2">
    <div class="form-group">
        <label class="font-weight-bold">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        @if ($fieldInfo->name == 'amount_of_funding' || $fieldInfo->name == 'funding_amount' ||
            $fieldInfo->name == 'revenue' || $fieldInfo->name == 'cost' || $fieldInfo->name == 'budget' ||
            $fieldInfo->name == 'total_profit'
            )
            <span id="" role="alert">
                (No commas)
            </span>
        @endif
        <div class="input-group mb-2">
            <div class="input-group-prepend">
                <select style="margin-top: 5px;" class="custom-select" name="currency_{{ $fieldInfo->name }}" id="currency_select_{{ $fieldInfo->name }}">
                  <option disabled selected>Choose...</option>
                </select>
            </div>
            <input type="decimal" autocomplete="off" name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" value="{{ (old($fieldInfo->name) == '') ?  number_format(($value == null) ? 0.00 : $value, 2, '.', ',') : old($fieldInfo->name) }}" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control form-validation"
            {{ ($fieldInfo->required == 1) ? 'required' : '' }} step="0.01" placeholder="{{ $fieldInfo->placeholder }}"
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
        $("#{{ $fieldInfo->name }}").on('focus click' , function() {
            $(this).val('');

        });

        $("#{{ $fieldInfo->name }}").on('change' , function() {
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
