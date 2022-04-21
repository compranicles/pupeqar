<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('View User') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <tr>
                                    <th>First Name</th>
                                    <td>{{ $user->first_name }}</td>
                                </tr>
                                <tr>
                                    <th>Middle Name</th>
                                    <td>{{ $user->middle_name ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Last Name</th>
                                    <td>{{ $user->last_name }}</td>
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
                                        @forelse ($roles as $role)
                                            @if ($loop->last)
                                                {{ $role->name }}
                                            @else
                                                {{ $role->name }},
                                            @endif
                                        @empty
                                            -
                                        @endforelse
                                    </td>
                                </tr>
                            </table>
                        </div>
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