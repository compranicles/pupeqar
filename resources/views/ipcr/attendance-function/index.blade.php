<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Attendance in University and College Functions') }}
        </h2>
    </x-slot>

    <div class="container">
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
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <button id="add_uni" class="btn btn-success">
                                    <i class="bi bi-plus"></i> Add Attended University Function
                                </button>
                            </div>
                            <div class="d-inline mr-2">
                                <button id="add_col" class="btn btn-success">
                                    <i class="bi bi-plus"></i> Add Attended College Function
                                </button>
                            </div>
                            {{-- @if (in_array(6, $roles)) --}}
                                <div class="d-inline mr-2">
                                    <a id="man_uni" href="{{ route('college-function-manager.index') }}" class="btn btn-warning text-dark">
                                        Manage College Functions
                                    </a>
                                </div>
                            {{-- @endif --}}
                            {{-- @if (in_array(8, $roles)) --}}
                                <div class="d-inline mr-2">
                                    <a id="man_uni" href="{{ route('university-function-manager.index') }}" class="btn btn-warning text-dark">
                                        Manage University Functions
                                    </a>
                                </div>
                            {{-- @endif --}}
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="quarterFilter" class="mr-2">Quarter Period: </label>
                                    <div class="d-flex">
                                        <select id="quarterFilter" class="custom-select" name="quarter">

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
                        <hr>
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="attendance_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Brief Description of Activity</th>
                                        <th>Date Started</th>
                                        <th>Date Completed</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Added</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($attendedFunctions as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >{{ $row->activity_description }}</td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >{{ $row->start_date }}</td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >{{ $row->end_date }}</td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >
                                            {{ $row->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >
                                            {{ $row->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >
                                            <?php
                                            $created_at = strtotime( $row->created_at );
                                            $created_at = date( 'M d, Y h:i A', $created_at );
                                            ?>
                                            {{ $created_at }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('attendance-function.show', $row->id) }}' " >
                                        <?php
                                            $updated_at = strtotime( $row->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('attendance-function.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                                <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-request="{{ $row->activity_description }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
                                                <a href="{{ url('submissions/check/30/'.$row->id) }}" class="btn btn-sm btn-success">Submit</a>
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
                $('#attendance_table').DataTable();
            } );

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
        <script>
            var table =  $("#attendance_table").DataTable({
                "searchCols": [
                    null,
                    null,
                    null,
                    null,
                    { "search": "{{ $currentQuarterYear->current_quarter }}" },
                    { "search": "{{ $currentQuarterYear->current_year }}" },
                    null,
                    null,
                    null,
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

            // var collegeIndex = 0;
            // $("#admin_table th").each(function (i) {
            //     if ($($(this)).html() == "College/Branch/Campus/Office") {
            //         collegeIndex = i; return false;

            //     }
            // });

            // $.fn.dataTable.ext.search.push(
            //     function (settings, data, dataIndex) {
            //         var selectedItem = $('#collegeFilter').val()
            //         var college = data[collegeIndex];
            //         if (selectedItem === "" || college.includes(selectedItem)) {
            //             return true;
            //         }
            //         return false;
            //     }
            // );


            // $("#collegeFilter").change(function (e) {
            //     table.draw();
            // });

            table.draw();
        </script>
    @endpush
</x-app-layout>
