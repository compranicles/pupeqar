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
                        <div class="table-responsive">
                            <table class="table" id="syllabus_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Course Title</th>
                                        <th>Assigned Task</th>
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
                                        <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >{{ $syllabus->updated_at }}</td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('syllabus.edit', $syllabus->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                                <button type="button" value="{{ $syllabus->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-syllabus="{{ $syllabus->course_title }}"><i class="bi bi-trash"></i> Delete</button>
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
     @endpush
</x-app-layout>