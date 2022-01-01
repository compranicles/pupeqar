<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Course Syllabus') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if (($accomplishment = Session::get('edit_syllabus_success')) && ($action = Session::get('action')) )
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $accomplishment }} has been {{ $action }}
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
                        <div class="row">
                            <div class="d-flex mr-2">
                                <div class="col-md-6">
                                    <label for="taskFilter" class="mr-2">Assigned Task: </label>
                                    <select id="taskFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach ($syllabiTask as $task)
                                        <option value="{{ $task->name }}">{{ $task->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="collegeFilter" class="mr-2">College/Branch/Campus/Office where committed: </label>
                                    <select id="collegeFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach($syllabus_in_colleges as $college)
                                        <option value="{{ $college->name }}">{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="syllabus_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Course Title</th>
                                        <th>Assigned Task</th>
                                        <th>College/Branch/Campus/Office</th>
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
                                            <?php $updated_at = strtotime( $syllabus->updated_at );
                                                $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>  
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('syllabus.edit', $syllabus->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                                <button type="button" value="{{ $syllabus->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-syllabus="{{ $syllabus->course_title }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
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
             $('#syllabus_table').DataTable();
         } );

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
     <script>
         var table =  $("#syllabus_table").DataTable();

          var taskIndex = 0;
            $("#syllabus_table th").each(function (i) {
                if ($($(this)).html() == "Assigned Task") {
                    taskIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#taskFilter').val()
                    var task = data[taskIndex];
                    if (selectedItem === "" || task.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            var collegeIndex = 0;
            $("#syllabus_table th").each(function (i) {
                if ($($(this)).html() == "College/Branch/Campus/Office") {
                    collegeIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#collegeFilter').val()
                    var college = data[collegeIndex];
                    if (selectedItem === "" || college.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            $("#taskFilter").change(function (e) {
                table.draw();
            });

            $("#collegeFilter").change(function (e) {
                table.draw();
            });

            table.draw();
     </script>
     @endpush
</x-app-layout>