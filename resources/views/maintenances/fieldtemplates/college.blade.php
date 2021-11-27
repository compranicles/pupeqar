<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-3">
    <div class="form-group">
        <label>{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
            <select name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" class="form-control custom-select form-validation" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
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
                <option value="{{ $row->id }}" {{ ($college_id == $row->id) ? 'selected' : '' }}>{{ $row->name }}</option>
                @endforeach
               
            </select>
            
        </div>
    </div>

@push('scripts')
    <script>
        $('#college_id').on('input', function(){
            var collegeId = $('#college_id').val();
            $('#department_id').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get('/departments/options/'+collegeId, function (data){

                data.forEach(function (item){
                    $("#department_id").append(new Option(item.name, item.id));
                });
            });
        });

        $(function() {
            
            var collegeId = $('#college_id').val();
            $('#department_id').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get('/departments/options/'+collegeId, function (data){

                data.forEach(function (item){
                    $("#department_id").append(new Option(item.name, item.id));
                    
                });
                document.getElementById("department_id").value = "{{ $department_id }}";
            });
        
        });
    </script>
@endpush