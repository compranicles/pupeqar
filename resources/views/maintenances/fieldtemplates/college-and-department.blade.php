    <div class="col-md-6">
        <div class="form-group">
            <label>Colleges/Campus/Branch/Office to commit this accomplishment</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
            <select name="college_id" id="college" class="form-control custom-select form-validation" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
                @endswitch
                >
                <option value="" selected disabled>Choose...</option>
                @foreach ($colleges as $row)
                <option value="{{ $row->id }}" {{ ($college == $row->id) ? 'selected' : '' }}>{{ $row->name }}</option>
                @endforeach
               
            </select>
            
        </div>
    </div>
    <div class="col-md-6">
        <label>Department to commit this accomplishment</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>

        <select name="department_id" id="department" class="form-control custom-select form-validation" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
             @endswitch
            >
            <option value="" selected disabled>Choose...</option>
        </select>
    </div>


@push('scripts')
    <script>
        $('#college').on('input', function(){
            var collegeId = $('#college').val();
            $('#department').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get('/departments/options/'+collegeId, function (data){

                data.forEach(function (item){
                    $("#department").append(new Option(item.name, item.id));
                });
            });
        });

        $(function() {
            
            var collegeId = $('#college').val();
            $('#department').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get('/departments/options/'+collegeId, function (data){

                data.forEach(function (item){
                    $("#department").append(new Option(item.name, item.id));
                    
                });
                document.getElementById("department").value = "{{ $department }}";
            });
        
        });
    </script>
@endpush