<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add Sector, College/Branch/Campus/Office, and Department') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('account') }}"><i class="bi bi-chevron-double-left"></i>Back to my account.</a>
                </p>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('offices.update', $office->id) }}" method="post">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group input-group-md">
                                        <label for="">Sector</label>
                                        <select name="sector" id="sector" class="{{ $errors->has('sector') ? 'is-invalid' : '' }} form-control custom-select form-validation sector" required>
                                            <option value="">Choose</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group input-group-md">
                                        <label for="">College/Branch/Campus/Office</label>
                                        <select name="cbco" id="cbco" class="{{ $errors->has('cbco') ? 'is-invalid' : '' }} form-control custom-select form-validation cbco" required>
                                            <option value="">Choose</option>
                                            @foreach($cbco as $row)
                                            <option value="{{ $row->id }}" {{ $office->college_id == $row->id ? 'selected' : '' }}>{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group input-group-md">
                                        <label for="">Department</label>
                                        <select name="department" id="department" class="{{ $errors->has('department') ? 'is-invalid' : '' }} form-control custom-select form-validation department" required>
                                            <option value="">Choose</option>
                                            @foreach($departments as $department)
                                            <option value="{{ $department->id }}" {{ $office->department_id == $department->id ? 'selected' : '' }}>{{ $department->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <a href="{{ url()->previous() }}" class="btn btn-secondary mr-2">Cancel</a>
                                            <button type="submit" id="submit" class="btn btn-success">Save</button>
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
    <script src="{{ asset('dist/selectize.min.js') }}"></script>
    <script>
         $("#cbco").selectize({
              sortField: "text",
          });
    </script>
    <script>
        $(function() {
            var collegeId = "{{ $office->college_id }}";
            var link1 = "{{ url('maintenances/sectors/name/:id') }}";
			var url1 = link1.replace(':id', collegeId);
            $.get(url1, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#sector").append(new Option(item.name, item.id));
                        $("#sector").val(item.id);
                    });
                }

            });
        })
    </script>
    <script>
        $('#cbco').on('input', function(){
            var collegeId = $('#cbco').val();
            $('#department').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            var link = "{{ url('departments/options/:id') }}";
			var url = link.replace(':id', collegeId);
            $.get(url, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#department").append(new Option(item.name, item.id));

                    });
                }
            });

            $('#sector').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            var link1 = "{{ url('maintenances/sectors/name/:id') }}";
			var url1 = link1.replace(':id', collegeId);
            $.get(url2, function (data){
                if (data != '') {
                    data.forEach(function (item){
                        $("#sector").append(new Option(item.name, item.id));
                        $("#sector").val(item.id);
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
