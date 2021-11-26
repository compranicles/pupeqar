<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Expert Service Rendered in Academic Journals/Books/Publication/Newsletter/Creative Works') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('faculty.expert-service-in-academic.store' ) }}" method="post">
                            @csrf
                            @include('extension-programs.form', ['formFields' => $expertServiceAcademicFields1])
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-group">
                                        <label>Colleges/Campus/Branch/Office where you commit the accomplishment</label><span style="color: red;"> *</span>
    
                                        <select name="college_id" id="college" class="form-control custom-select"  required>
                                            <option value="" selected disabled>Choose...</option>
                                            @foreach ($colleges as $college)
                                            <option value="{{ $college->id }}">{{ $college->name }}</option>
                                            @endforeach
                                           
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Department where you commit the accomplishment</label><span style="color: red;"> *</span>

                                    <select name="department_id" id="department" class="form-control custom-select" required>
                                        <option value="" selected disabled>Choose...</option>
                                    </select>
                                </div>
                            </div>
                            @include('extension-programs.form', ['formFields' => $expertServiceAcademicFields2])
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <button type="submit" id="submit" class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $('#college').on('blur', function(){
                var collegeId = $('#college').val();
                $('#department').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
                $.get('/departments/options/'+collegeId, function (data){

                    data.forEach(function (item){
                        $("#department").append(new Option(item.name, item.id));
                    });
                });
            });
        </script>
        <script>
            $('#from').on('input', function(){
                var date = new Date($('#from').val());
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();
                // alert([day, month, year].join('-'));
                // document.getElementById("target_date").setAttribute("min", [day, month, year].join('-'));
                document.getElementById('to').setAttribute('min', [year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
                $('#to').val([year, month, day.toLocaleString(undefined, {minimumIntegerDigits: 2})].join('-'));
            });

            function validateForm() {
                var isValid = true;
                $('.form-validation').each(function() {
                    if ( $(this).val() === '' )
                        isValid = false;
                });
                return isValid;
            }
        </script>
    @endpush
</x-app-layout>