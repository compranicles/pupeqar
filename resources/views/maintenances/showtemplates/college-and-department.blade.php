<tr>
    <th>Colleges/Campus/Branch/Office to commit this accomplishment</th>
    <td id="college"></td>
</tr>
<tr>
    <th>Department to commit this accomplishment</th>
    <td id="department"></td>
</tr>

@push('scripts')
    <script>
        $('#college').ready(function (){
            $.get("{{ route('college.name', $college) }}", function (data){
                $('#college').html(data);
            });
        });
    </script>
    <script>
        $('#department').ready(function (){
            $.get("{{ route('department.name', $department) }}", function (data){
                $('#department').html(data);
            });
        });
    </script>
@endpush