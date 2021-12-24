<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Student Attended Seminars and Trainings') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if ($message = Session::get('student_success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>              
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('student-training.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Student Attended Seminars and Trainings</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="row">
                            <div class="d-flex mr-2">
                                <div class="col-md-12">
                                    <label for="classFilter" class="mr-2">Classification: </label>
                                    <select id="classFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach ($trainingClassifications as $classification)
                                        <option value="{{ $classification->name }}">{{ $classification->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="student_training_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name of Student</th>
                                        <th>Title</th>
                                        <th>Classification</th>
                                        <th>Organization</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($student_trainings as $row)
                                    <tr class="tr-hover" role="button">
                                        <td><a href="{{ route('student-training.show', $row->id) }}"></a>{{ $loop->iteration }}</td>
                                        <td>{{ $row->name_of_student }}</td>
                                        <td>{{ $row->title }}</td>
                                        <td>{{ $row->classification_name }}</td>
                                        <td>{{ $row->organization }}</td>
                                        <td>
                                            <?php $updated_at = strtotime( $row->updated_at );
                                                $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>  
                                                {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('student-training.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                                <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-student="{{ $row->title }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
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
             $('#student_training_table').DataTable();
         } );

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var rtmmiTitle = button.getAttribute('data-bs-student')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = rtmmiTitle

          var url = '{{ route("student-training.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
          
        });
     </script>
     <script>
         $('#student_training_table').on('click', 'tbody td', function(){
                window.location = $(this).closest('tr').find('td:eq(0) a').attr('href');
            });
            
     </script>
     <script>
         var table =  $("#student_training_table").DataTable();
          var classIndex = 0;
            $("#student_training_table th").each(function (i) {
                if ($($(this)).html() == "Classification") {
                    classIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#classFilter').val()
                    var classification = data[classIndex];
                    if (selectedItem === "" || classification.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            $("#classFilter").change(function (e) {
                table.draw();
            });

            table.draw();
     </script>
     @endpush
</x-app-layout>