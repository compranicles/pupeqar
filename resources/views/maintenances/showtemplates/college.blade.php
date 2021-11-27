<tr>
    <th>{{ $fieldInfo->label }}</th>
    <td id="{{ $fieldInfo->name }}"></td>
</tr>


@push('scripts')
    <script>
        $('#{{ $fieldInfo->name }}').ready(function (){
            $.get("{{ route('college.name', $value) }}", function (data){
                $('#{{ $fieldInfo->name }}').html(data);
            });
        });
    </script>
@endpush