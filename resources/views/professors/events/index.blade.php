<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Events Attended') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($message = Session::get('success_event'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('professor.events.create') }}" class="btn btn-success">Add New Events</a>
                            </div>
            
                        </div>  
                        <hr>
                        <table class="table" id="user_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                    <tr>
                                        <td>{{ $event->id }}</td>
                                        <td>{{ $event->name }}</td>
                                        <td>@foreach ($event_types as $event_type){{ $event_type->name }} @endforeach</td>
                                        <td>{{ $event->start_date }}</td>
                                        <td>{{ $event->end_date }}</td>
                                        <td>
                                            @if ($event->status == 0)
                                              <p class="badge badge-warning text-wrap m-0"><i>Pending</i></p>
                                            @elseif($event->status == 1)
                                              <p class="badge badge-success text-wrap m-0"><i>Accepted</i> </p>
                                            @elseif($event->status == 2)
                                              <p class="badge badge-danger text-wrap m-0"><i>Rejected</i></p>
                                            @elseif($event->status == 3)
                                              <p class="badge badge-dark text-wrap m-0"><i>Closed</i></p>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.users.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('professor.events.edit', $event->id) }}"  class="btn btn-primary btn-sm">Edit</a>
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
     @push('scripts')
     <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
     <script>
         $(document).ready( function () {
             $('#user_table').DataTable();
         } );
     </script>
     @endpush
</x-app-layout>