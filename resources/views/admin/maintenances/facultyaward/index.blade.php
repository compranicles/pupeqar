<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Maintenances > Faculty Awards Classification') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('admin.maintenances.index') }}" class="btn btn-secondary mb-2"><i class="fas fa-arrow-left mr-2"></i> Back</a>
                            </div>
                            <div class="d-inline ">
                                <a href="{{ route('admin.maintenances.facultyaward.create') }}" class="btn btn-success mb-2">Add a Category</a>
                            </div>
                        </div>
                        <hr>
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table" id="facua_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($faculty_awards as $faculty_award)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $faculty_award->name }}</td>
                                            <td>
                                                <form action="{{ route('admin.maintenances.facultyaward.destroy', $faculty_award->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.maintenances.facultyaward.edit', $faculty_award->id) }}"  class="btn btn-primary btn-sm">Edit</a>
                                                        <input type="submit" class="btn btn-danger btn-sm" value="Delete">
                                                    </div>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
     <script>
         $(document).ready( function () {
             $('#facua_table').DataTable();
         } );
     </script>
     <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
     @endpush
</x-app-layout>