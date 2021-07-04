<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Users') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-success mb-2">Add New User</a>
                            </div>
            
                            <div class="d-inline ">
                                <a href="{{ route('admin.users.invite') }}" class="btn btn-success mb-2">Invite a User</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="user_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Date Joined</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->first_name." ".$user->middle_name." ".$user->last_name." ".$user->suffix }}</td>
                                            <td>{{ $user->created_at }}</td>
                                            <td>
                                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ route('admin.users.show', $user->id) }}"  class="btn btn-success btn-sm">View</a>
                                                        <a href="{{ route('admin.users.edit', $user->id) }}"  class="btn btn-primary btn-sm">Edit</a>
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
     <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
     <script>
         $(document).ready( function () {
             $('#user_table').DataTable({
             });
         } );
     </script>
     @endpush
</x-app-layout>