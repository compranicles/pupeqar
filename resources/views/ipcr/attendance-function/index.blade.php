<x-app-layout>
    @section('title', 'Attendance in University/College Functions |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Attendance in University and College/ Office Functions</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-index">
                        <i class="bi bi-check-circle"></i> {{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('cannot_access'))
                <div class="alert alert-danger alert-index">
                    {{ $message }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row ml-1 mr-1">
                            <div class="d-flex">
                                <div class="mr-2">
                                    <button id="add_dept" class="btn btn-success" data-toggle="modal" data-target="#departmentModal">
                                        <i class="bi bi-plus"></i> Department/Section Functions
                                    </button>
                                </div>
                                <div class="mr-2">
                                    <button id="add_col" class="btn btn-success" data-toggle="modal" data-target="#collegeModal">
                                        <i class="bi bi-plus"></i> College/Office Functions
                                    </button>
                                </div>
                                <div class="mr-2">
                                    <button id="add_uni" class="btn btn-success" data-toggle="modal" data-target="#universityModal">
                                        <i class="bi bi-plus"></i> University Functions
                                    </button>
                                </div>
                                @if (in_array(5, $roles))
                                    <div class="mr-2">
                                        <a id="man_uni" href="{{ route('department-function-manager.index') }}" class="btn btn-warning text-dark">
                                            <i class="bi bi-gear-fill"></i> Manage Department/Section Functions
                                        </a>
                                    </div>
                                @endif
                                @if (in_array(6, $roles))
                                    <div class="mr-2">
                                        <a id="man_uni" href="{{ route('college-function-manager.index') }}" class="btn btn-warning text-dark">
                                            <i class="bi bi-gear-fill"></i> Manage College/Office Functions
                                        </a>
                                    </div>
                                @endif
                                @if (in_array(8, $roles))
                                    <div class="mr-2">
                                        <a id="man_uni" href="{{ route('university-function-manager.index') }}" class="btn btn-warning text-dark">
                                            <i class="bi bi-gear-fill"></i> Manage University Functions
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <hr>
                        @include('instructions')
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="attendance_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Brief Description of Activity</th>
                                        <th>Classification</th>
                                        <th>Date Started</th>
                                        <th>Date Completed</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendedFunctions as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >{{ $row->activity_description }}</td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >{{ $row->classification_name }}</td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >
                                        <?php
                                            $start_date = strtotime( $row->start_date );
                                            $start_date = date( 'F d, Y', $start_date );
                                            ?>
                                            {{ $start_date }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >
                                        <?php
                                            $end_date = strtotime( $row->end_date );
                                            $end_date = date( 'F d, Y', $end_date );
                                            ?>
                                            {{ $end_date }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >
                                            {{ $row->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >
                                            {{ $row->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >
                                        <?php
                                            $updated_at = strtotime( $row->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('attendance-function.show', $row->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">View</a>
                                                <a href="{{ route('attendance-function.edit', $row->id) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                <button type="button"  value="{{ $row->id }}" class="btn btn-sm btn-danger d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-request="{{ $row->activity_description }}">Delete</button>
                                                @if ($submissionStatus[33][$row->id] == 0)
                                                    <a href="{{ url('submissions/check/33/'.$row->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">Submit</a>
                                                @elseif ($submissionStatus[33][$row->id] == 1)
                                                    <a href="{{ url('submissions/check/33/'.$row->id) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">Submitted {{ $submitRole[$row->id] == 'f' ? 'as Faculty' : 'as Admin' }}</a>
                                                @elseif ($submissionStatus[33][$row->id] == 2)
                                                    <a href="{{ route('attendance-function.edit', $row->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                @endif
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

    @include('ipcr.attendance-function.unversity', compact('universityFunctions'))
    @include('ipcr.attendance-function.college', compact('collegeFunctions'))
    @include('ipcr.attendance-function.department', compact('departmentFunctions'))
     {{-- Delete Modal --}}
     @include('delete')



    @push('scripts')
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
        <script>
            window.setTimeout(function() {
                $(".alert-index").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);

            $('#attendance_table').DataTable();

            //Item to delete to display in delete modal
            var deleteModal = document.getElementById('deleteModal')
            deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            var id = button.getAttribute('value')
            var requestTitle = button.getAttribute('data-bs-request')
            var itemToDelete = deleteModal.querySelector('#itemToDelete')
            itemToDelete.textContent = requestTitle

            var url = '{{ route("attendance-function.destroy", ":id") }}';
            url = url.replace(':id', id);
            document.getElementById('delete_item').action = url;

            });
        </script>
    @endpush
</x-app-layout>
