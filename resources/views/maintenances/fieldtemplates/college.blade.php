<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-3">
    <div class="form-group">
        <label class="{{ ($fieldInfo->required == 1) ? 'font-weight-bold' : '' }}">{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
            <select name="{{ $fieldInfo->name }}" id="college_id" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control custom-select form-validation" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
                @foreach ($colleges as $row)
                <option value="{{ $row->id }}" {{ (old($fieldInfo->name) == '') ?  (($college_id == $row->id) ? 'selected' : '') : ((old($fieldInfo->name) == $row->id) ? 'selected' : '') }} >{{ $row->name }}</option>
                @endforeach

            </select>
            <!-- @if ($colleges !== []) -->
            <span id="" role="alert">
                <p><a href="{{ route('offices.create') }}" style="color: maroon;">Add College/Branch/Campus/Offices Where You Are Reporting.</a></p>
            </span>
            <!-- @endif -->
            @error($fieldInfo->name)
                <span class='invalid-feedback' role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

@push('scripts')
    <script src="{{ asset('dist/selectize.min.js') }}"></script>
    <script>
         $("#college_id").selectize({
              sortField: "text",
          });
    </script>
    <script>
        $('#college_id').on('input', function(){
            var collegeId = $('#college_id').val();
            $('#department_id').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            var url = "{{ url('departments/options/:id') }}";
			var api = url.replace(':id', collegeId);
            $.get(api, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#department_id").append(new Option(item.name, item.id));
                    });
                }
                if ("{{ old('department_id') }}" == '')
                    document.getElementById("department_id").value = "{{ $department_id }}";
                else
                    document.getElementById("department_id").value = "{{ old('department_id') }}";
            });
        });
    </script>
    <script>
        var collegeId = $('#college_id').val();

            if (collegeId == '') {
                document.getElementById("department_id").value = "";
            }
            else {
                var url = "{{ url('departments/options/:id') }}";
				var api = url.replace(':id', collegeId);
				$.get(api, function (data){
                    if (data != '') {
                        data.forEach(function (item){
                            $("#department_id").append(new Option(item.name, item.id));

                        });
                    }
                    if ("{{ old('department_id') }}" == '')
                    document.getElementById("department_id").value = "{{ $department_id }}";
                    else
                        document.getElementById("department_id").value = "{{ old('department_id') }}";
                });
            }
    </script>
@endpush
