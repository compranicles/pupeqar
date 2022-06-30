<x-app-layout>
        @section('title', 'Course Syllabi |')
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Course Syllabus</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if (($accomplishment = Session::get('edit_syllabus_success')) && ($action = Session::get('action')) )
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $accomplishment }} has been {{ $action }}
                </div>
                @endif
                @if ($message = Session::get('cannot_access'))
                <div class="alert alert-danger alert-index">
                    {{ $message }}
                </div>
                @endif
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-index">
                    {{ $message }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('syllabus.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Course Syllabus</a>
                            </div>
                        </div>
                        <hr>
                        @include('instructions')
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="syllabus_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Course Title</th>
                                        <th>Assigned Task</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($syllabi as $syllabus)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >{{ $syllabus->course_title }}</td>
                                        <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >{{ $syllabus->assigned_task_name }}</td>
                                        <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >{{ $syllabus->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >
                                            {{ $syllabus->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >
                                            {{ $syllabus->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' ">
                                        <?php
                                            $updated_at = strtotime( $syllabus->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('syllabus.show', $syllabus->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">View</a>
                                                <a href="{{ route('syllabus.edit', $syllabus->id) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                <button type="button"  value="{{ $syllabus->id }}" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-syllabus="{{ $syllabus->course_title }}">Delete</button>
                                                @if ($submissionStatus[16][$syllabus->id] == 0)
                                                    <a href="{{ url('submissions/check/16/'.$syllabus->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                @elseif ($submissionStatus[16][$syllabus->id] == 1)
                                                    <a href="{{ url('submissions/check/16/'.$syllabus->id) }}" class="btn btn-sm btn-success">Submitted</a>
                                                @elseif ($submissionStatus[16][$syllabus->id] == 2)
                                                    <a href="{{ route('syllabus.edit', $syllabus->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
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

             $('#syllabus_table').DataTable();

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var syllabusTitle = button.getAttribute('data-bs-syllabus')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = syllabusTitle

          var url = '{{ route("syllabus.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;

        });
     </script>
     @endpush
</x-app-layout>
