<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Event Types') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="mb-3 ml-1">
                        <div class="d-inline mr-2">
                            <a href="{{ route('admin.event_types.create') }}" class="btn btn-success">Add New User</a>
                        </div>
                    </div>  
                    <hr>
                    <table class="table" id="event_type_table">
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
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
     <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
     <script>
         $(document).ready( function () {
             $('#event_type_table').DataTable();
         } );
     </script>
     @endpush
</x-app-layout>