<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Show User') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <th>ID</th>
                                <td>{{ $user->id }}</td>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <td>{{ $user->first_name }}</td>
                            </tr>
                            <tr>
                                <th>Middle Name</th>
                                <td>{{ $user->middle_name }}</td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td>{{ $user->last_name }}</td>
                            </tr>
                            <tr>
                                <th>Suffix</th>
                                <td>{{ $user->suffix }}</td>
                            </tr>
                            <tr>
                                <th>Date of Birth</th>
                                <td>{{ $user->date_of_birth }}</td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td>{{ $user->email }}</td>
                            </tr>
                            <tr>
                                <th>Role</th>
                                <td>
                                    @if ($user->role_id == 1)
                                        {{ __('Admin') }}
                                    @elseif ($user->role_id == 2)
                                        {{ __('HAP') }}
                                    @elseif($user->role_id == 3)
                                        {{ __('Professor') }}
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <div class="mb-0">
                            <div class="d-flex justify-content-end align-items-baseline">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to List</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</x-app-layout>