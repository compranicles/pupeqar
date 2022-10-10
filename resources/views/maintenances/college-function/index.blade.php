<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="font-weight-bold mr-2">College/Office Functions</h3>
                <p>
                    <a class="back_link" href="{{ route('attendance-function.index') }}"><i class="bi bi-chevron-double-left"></i>Back to Attendance in University and College Functions</a>
                </p>
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-index">
                        <i class="bi bi-check-circle"></i> {{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-index">
                        {{ $message }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a id="add_func" href="{{ route('college-function-manager.create') }}" class="btn btn-success">
                                    <i class="bi bi-plus"></i> Add College/Office Function
                                </a>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="functions_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>College/Office</th>
                                        <th>Brief Description of Activity</th>
                                        <th>Remarks</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($collegeFunctions as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('college-function-manager.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('college-function-manager.show', $row->id) }}' " >{{ $row->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('college-function-manager.show', $row->id) }}' " >{{ $row->activity_description }}</td>
                                        <td onclick="window.location.href = '{{ route('college-function-manager.show', $row->id) }}' " >{{ $row->remarks }}</td>
                                        <td onclick="window.location.href = '{{ route('college-function-manager.show', $row->id) }}' " >
                                            @php
                                                $updated_at = strtotime( $row->updated_at );
                                                $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            @endphp
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('college-function-manager.edit', $row->id) }}" role="button" class="btn btn-sm btn-warning">Edit</a>
                                                <button type="button" value="{{ $row->id }}" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-request="{{ $row->activity_description }}">Delete</button>
                                            </div>
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

    {{-- Delete Modal --}}
    @include('delete')

    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 4000);

        $(document).ready( function () {
            $('#functions_table').DataTable();
        } );

        //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget
        var id = button.getAttribute('value')
        var requestTitle = button.getAttribute('data-bs-request')
        var itemToDelete = deleteModal.querySelector('#itemToDelete')
        itemToDelete.textContent = requestTitle

        var url = '{{ route("college-function-manager.destroy", ":id") }}';
        url = url.replace(':id', id);
        document.getElementById('delete_item').action = url;

        });
    </script>
    @endpush
</x-app-layout>
