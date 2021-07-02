<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Users') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif
                <div class="mb-3 ml-1">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-success">Add New User</a>
                </div>
                <div class="card">
                    <div class="card-body">
                        
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>{{ $user->first_name." ".$user->middle_name." ".$user->last_name." ".$user->suffix }}</td>
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
</x-app-layout>