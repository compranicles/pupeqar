<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Events') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="col-lg-12">
            @if ($message = Session::get('success_event'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 ml-1">
                        <div class="d-inline mr-2">
                            <a href="{{ route('admin.events.create') }}" class="btn btn-success">Add Event</a>
                        </div>
                    </div>  
                    <hr>
                    <div class="table-responsive">
                        <table class="table" id="event_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Event Name</th>
                                    <th>Event Type</th>
                                    <th>Created By</th>
                                    <th>Date Created</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($events as $event)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $event->name }}</td>
                                    <td>
                                        @foreach ($event_types as $event_type)
                                            {{ (($event->event_type_id == $event_type->id) ?  $event_type->name: '') }}
                                        @endforeach
                                    </td>
                                    <td>{{ $event->first_name." ".$event->last_name }}</td>
                                    <td>{{ $event->created_at }}</td>
                                    <td>
                                        @if ($event->status == 0)
                                            <a class="btn btn-warning btn-sm btn-disabled text-dark">Not Reviewed</a>
                                        @elseif($event->status == 1)
                                            <a class="btn btn-sm btn-success btn-disabled">Accepted</a>
                                        @elseif($event->status == 2)
                                            <a class="btn btn-sm btn-danger btn-disabled">Rejected</a>
                                        @elseif($event->status == 3)
                                            <a class="btn btn-sm btn-dark btn-disabled">Closed</a>
                                        @endif
                                    </td>
                                    <td>
                                        <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="btn-group" role="group">
                                                @if ($event->status == 0)
                                                    <a href="{{ route('admin.events.accept', $event->id) }}"  class="btn btn-success btn-sm">Accept</a>
                                                    <a href="{{ route('admin.events.reject', $event->id) }}"  class="btn btn-danger btn-sm">Reject</a>
                                                    <a href="{{ route('admin.events.close', $event->id) }}"  class="btn btn-outline-danger btn-sm">Close</a>
                                                @elseif ($event->status == 1)
                                                    <a href="{{ route('admin.events.reject', $event->id) }}"  class="btn btn-danger btn-sm">Reject</a>
                                                    <a href="{{ route('admin.events.close', $event->id) }}"  class="btn btn-outline-danger btn-sm">Close</a>
                                                @elseif ($event->status == 2)
                                                    <a href="{{ route('admin.events.accept', $event->id) }}"  class="btn btn-success btn-sm">Accept</a>
                                                    <a href="{{ route('admin.events.close', $event->id) }}"  class="btn btn-outline-danger btn-sm">Close</a>
                                                @endif
                                                <a href="{{ route('admin.events.edit', $event->id) }}"  class="btn btn-primary btn-sm">Edit</a>
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
    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script>
     <script>
         $(document).ready( function () {
             $('#event_table').DataTable();
         } );
     </script>
     @endpush
</x-app-layout>