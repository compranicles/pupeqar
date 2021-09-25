<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Invite a User') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-lg-6">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.sendinvite') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Email Address') }}" />
                                        <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email"
                                                    :value="old('email')" required />
                                        <x-jet-input-error for="email"></x-jet-input-error>
                                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-end align-items-baseline">
                                    <x-jet-button>
                                        {{ __('Invite') }}
                                    </x-jet-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h4>
                            Invited Emails
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="invite_table">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Token</th>
                                        <th>Status</th>
                                        <th>Date Invited</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invites as $invite)
                                    <tr>
                                        <td>{{ $invite->email }}</td>
                                        <td>{{ $invite->token }}</td>
                                        <td>@if ($invite->status == 0)
                                                Not Registered
                                            @else
                                                Registered
                                            @endif
                                        </td>
                                        <td>{{ $invite->created_at }}</td>
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
            $('#invite_table').DataTable();
        } );
    </script>
    @endpush
</x-app-layout>