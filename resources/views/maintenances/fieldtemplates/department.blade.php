<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-2">
    <label class="font-weight-bold" for="{{ $fieldInfo->name }}">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>

    <select name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control custom-select form-validation" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
            @endswitch
        >

        <option value="" selected disabled>Choose...</option>
        @foreach ($departments as $department)
        <option value="{{ $department->id }}" {{ $department->id == old('department_id', $department_id) ? 'selected' : '' }}>{{ $department->name }}</option>
        @endforeach
        {{-- <option value="0"></option> --}}
    </select>
    @error($fieldInfo->name)
        <span class='invalid-feedback' role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>

@push('scripts')
    <script src="{{ asset('dist/selectize.min.js') }}"></script>
    <script>
        //  $("#department_id").selectize({
        //       sortField: "text",
        //   });
    </script>
    <script>
         $(document).on('input', '#department_id', function(){
            var departmentId = $('#department_id').val();
            var url = "{{ url('maintenances/colleges/name/department/:id') }}";
			var api = url.replace(':id', departmentId);
            $.get(api, function (data){
                if (data != '') {
                    $('#college_id').val(data)
                }
                else{
                    $('#college_id').val('');
                }
            });
        });

        $(function () {
            var departmentID = $('#department_id').val();
            if (departmentID == '') {
                document.getElementById("college_id").value = "";
            }
            else {
                var url = "{{ url('maintenances/colleges/name/department/:id') }}";
                var api = url.replace(':id', departmentID);
                $.get(api, function (data){
                    if (data != '') {
                        $('#college_id').val(data)
                    }
                });
            }
        });

    </script>
@endpush
