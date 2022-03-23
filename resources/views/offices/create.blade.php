<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Add College/Branch/Campus/Office') }}
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
                        <form action="{{ route('offices.store') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group input-group-md">
                                        <label for="">Sector</label>
                                        <select readonly name="sector" id="sector" class="{{ $errors->has('sector') ? 'is-invalid' : '' }} form-control custom-select form-validation sector" required>
                                            <option value="" selected></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group input-group-md">
                                        <label for="">College/Branch/Campus/Office</label>
                                        <select name="cbco" id="cbco" class="{{ $errors->has('cbco') ? 'is-invalid' : '' }} form-control custom-select form-validation cbco" required>
                                            <option value="" selected>Choose</option>
                                            @foreach($cbco as $row)
                                            <option value="{{ $row->id }}">{{ $row->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group input-group-md" id="dept_div">
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
        $('#cbco').on('input', function(){
            var collegeId = $('#cbco').val();
            $.get('/departments/options/'+collegeId, function (data){
                if (data != '') {
                    $("#dept_div").empty();
                    $("#dept_div").append("<p>Departments Belong To The College/Branch/Campus/Office:</p>");
                    data.forEach(function (item){
                        $("#dept_div").append("<span class='badge bg-primary ml-1 mr-1'>"+item.name+"</span>");
                    });
                }
                else {
                    $("#dept_div").empty();
                    $("#dept_div").append("<p>No Departments Belong In The College/Branch/Campus/Office.</p>");
                }
            });

            $('#sector').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get('/maintenances/sectors/name/'+collegeId, function (data){
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