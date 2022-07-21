<tr>
    <th>{{ $fieldInfo->label }}</th>
    <td id="{{ $fieldInfo->name }}">{{ $value }}</td>
</tr>

{{-- @push('scripts')
    <script>
        $('#{{ $fieldInfo->name }}').ready(function (){
            if({{ $value  }} == "0"){
                $('#{{ $fieldInfo->name }}').html("N/A");
            }
            else{
                setTimeout(function (){
                $.get("{{ route('department.name', $value) }}", function (data){
                    $('#{{ $fieldInfo->name }}').html(data);
                }); }, Math.floor(Math.random() * (2500 - 1) + 1));
            }
        });
    </script>
@endpush --}}
