<x-app-layout>
    <!-- <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Awards and Recognition Received by the College/Branch/Campus/Office/Department') }}
        </h2>
    </x-slot> -->

        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Awards and Recognition Received by the College/Branch/Campus/Office/Department</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if ($message = Session::get('award_success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>
                @endif
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-index">
                    {{ $message }}
                </div>
                @endif
                @if ($message = Session::get('cannot_access'))
                <div class="alert alert-danger alert-index">
                    {{ $message }}
                </div>
            @endif
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-inline mr-2">
                                <a href="{{ route('college-department-award.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Awards and Recognition Received by the College/Branch/Campus/Office/Department</a>
                            </div>
                        </div>
                        <hr>
                        <!-- <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quarterFilter" class="mr-2">Quarter Period: </label>
                                    <div class="d-flex">
                                        <select id="quarterFilter" class="custom-select" name="quarterFilter">

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="yearFilter" class="mr-2">Year Covered:</label>
                                    <div class="d-flex">
                                        <select id="yearFilter" class="custom-select" name="yearFilter">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <hr>
                            </div>
                        </div> -->
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="college_department_award_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Name of Award</th>
                                        <th>Certifying Body</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($college_department_awards as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('college-department-award.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('college-department-award.show', $row->id) }}' " >{{ $row->name_of_award }}</td>
                                        <td onclick="window.location.href = '{{ route('college-department-award.show', $row->id) }}' " >{{ $row->certifying_body }}</td>
                                        <td onclick="window.location.href = '{{ route('college-department-award.show', $row->id) }}' " >{{ $row->report_quarter }}</td>
                                        <td onclick="window.location.href = '{{ route('college-department-award.show', $row->id) }}' " >{{ $row->report_year }}</td>
                                        <td>
                                        <?php
                                            $updated_at = strtotime( $row->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('college-department-award.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <button type="button" value="{{ $row->id }}" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-award="{{ $row->name_of_award }}">Delete</button>
                                                <a href="{{ url('submissions/check/21/'.$row->id) }}" class="btn btn-sm btn-success">Submit</a>
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
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 4000);

        $('#college_department_award_table').DataTable();

        //  $(document).ready( function () {
        //     var table = $('#college_department_award_table').DataTable({
        //         "searchCols": [
        //             null,
        //             null,
        //             null,
        //             // { "search": "{{ $currentQuarterYear->current_quarter }}" },
        //             // { "search": "{{ $currentQuarterYear->current_year }}" },
        //             null,
        //             null,
        //             null,
        //             null,
        //             null,
        //         ],
        //         initComplete: function () {
        //             this.api().columns(3).every( function () {
        //                 var column = this;
        //                 var select = $('#quarterFilter')
        //                     .on( 'change', function () {
        //                         var val = $.fn.dataTable.util.escapeRegex(
        //                             $(this).val()
        //                         );

        //                         column
        //                             .search( val ? '^'+val+'$' : '', true, false )
        //                             .draw();
        //                     } );

        //                 column.data().unique().sort().each( function ( d, j ) {
        //                     select.append( '<option value="'+d+'">'+d+'</option>' )
        //                 } );
        //             });

        //             this.api().columns(4).every( function () {
        //                 var column = this;
        //                 var select = $('#yearFilter')
        //                     .on( 'change', function () {
        //                         var val = $.fn.dataTable.util.escapeRegex(
        //                             $(this).val()
        //                         );

        //                         column
        //                             .search( val ? '^'+val+'$' : '', true, false )
        //                             .draw();
        //                     } );

        //                 column.data().unique().sort().each( function ( d, j ) {
        //                     select.append( '<option value="'+d+'">'+d+'</option>' )
        //                 } );
        //             });
        //         }
        //     });
        //  } );


         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var rtmmiTitle = button.getAttribute('data-bs-award')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = rtmmiTitle

          var url = '{{ route("college-department-award.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;

        });
     </script>
     @endpush
</x-app-layout>
