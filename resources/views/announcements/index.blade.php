<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Announcements') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Announcements</h2>
            </div>
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
                                <a href="{{ route('announcements.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Create Announcement</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="announcement_table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Subject</th>
                                        <th>Date Created</th>
                                        <th>Show/Hide</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($announcements as $announcement)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $announcement->subject }}</td>
                                        <td>{{ $announcement->created_at }}</td>
                                        <td>
                                            @if ($announcement->status == 1)
                                                <a href="{{ route('announcements.hide', $announcement->id) }}" class="btn btn-sm btn-outline-success">Active</a>
                                            @elseif($announcement->status == 2)
                                                <a href="{{ route('announcements.activate', $announcement->id) }}" class="btn btn-sm btn-outline-danger">Hidden</a>
                                            @endif
                                        </td>
                                        <td>
                                            <form action="{{ route('announcements.destroy', $announcement->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                <input type="hidden" name="_method" value="DELETE">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('announcements.show', $announcement->id) }}"  class="btn btn-primary btn-sm">View</a>
                                                    <a href="{{ route('announcements.edit', $announcement->id) }}"  class="btn btn-warning btn-sm">Edit</a>
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
             $('#announcement_table').DataTable();
         } );
     </script>
     @endpush
</x-app-layout>