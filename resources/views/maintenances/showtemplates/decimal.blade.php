<tr>
    <th>{{ $fieldInfo->label }}</th>
    <td><div id="currency"></div>{{ $value }}</td>
</tr>

@push('scripts')
    <script>
        $('#currency').ready(function (){
            $.get("{{ route('currency.name', $currency) }}", function (data){
                $('#currency').html(data);
            });
        });
    </script>
@endpush