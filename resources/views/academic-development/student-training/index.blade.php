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
                @if ($message = Session::get('cannot_access'))
                <div class="alert alert-danger alert-index">
                    {{ $message }}
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
                            <div class="col-md-3">
                                <label for="quarterFilter" class="mr-2">Quarter Period: </label>
                                <div class="d-flex">
                                    <select id="quarterFilter" class="custom-select" name="quarter">
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="yearFilter" class="mr-2">Year Added:</label>
                                <div class="d-flex">
                                    <select id="yearFilter" class="custom-select" name="yearFilter">
                                        
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
                                        <th>Organization</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($student_trainings as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >{{ $row->name_of_student }}</td>
                                        <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >{{ $row->title }}</td>
                                        <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >{{ $row->organization }}</td>
                                        <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >
                                            {{ $row->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >
                                            {{ $row->report_year }}
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
             var table = $('#student_training_table').DataTable({
                "searchCols": [
                    null,
                    null,
                    null,
                    null,
                    { "search": "{{ $currentQuarterYear->current_quarter }}" },
                    { "search": "{{ $currentQuarterYear->current_year }}" },
                    null
                ],
                initComplete: function () {
                    this.api().columns(4).every( function () {
                        var column = this;
                        var select = $('#quarterFilter')
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
        
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
        
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    });

                    this.api().columns(5).every( function () {
                        var column = this;
                        var select = $('#yearFilter')
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
        
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
        
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    });
                }
             });
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
     @endpush
</x-app-layout>