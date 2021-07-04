<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Event Types') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="col-lg-12">
            @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    {{ $message }}
                </div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 ml-1">
                        <div class="d-inline mr-2">
                            <a href="{{ route('admin.event_types.create') }}" class="btn btn-success">Add Event Type</a>
                        </div>
                    </div>  
                    <hr>
                    <div class="table-responsive">
                        <table class="table" id="type_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($eventtypes as $eventtype)
                                <tr>
                                    <td>{{ $eventtype->id }}</td>
                                    <td>{{ $eventtype->name }}</td>
                                    <td>{{ $eventtype->description }}</td>
                                    <td>
                                        <form action="{{ route('admin.event_types.destroy', $eventtype->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.event_types.edit', $eventtype->id) }}"  class="btn btn-primary btn-sm">Edit</a>
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
             $('#type_table').DataTable();
         } );
     </script>
     @endpush
</x-app-layout>