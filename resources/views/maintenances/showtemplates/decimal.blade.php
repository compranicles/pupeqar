<tr>
    <th>{{ $fieldInfo->label }}</th>
    <td><span id="currency_{{ $fieldInfo->name }}"></span> {{ number_format($value, 2, '.', ',') }}</td>
</tr>

@push('scripts')
    <script>
        $('#currency_{{ $fieldInfo->name }}').ready(function (){
            $.get("{{ route('currency.name', $currency) }}", function (data){
                $('#currency_{{ $fieldInfo->name }}').html(data);
            });
        });
    </script>
@endpush