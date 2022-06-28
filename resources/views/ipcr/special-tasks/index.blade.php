<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            @if (in_array(1, $roles))
                Special Tasks
            @elseif (in_array(3, $roles))
                Accomplishments Based on OPCR
            @endif
        </h2>
    </x-slot>

        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">
                @if (in_array(1, $roles))
                    Special Tasks
                @elseif (in_array(3, $roles))
                    Accomplishments Based on OPCR
                @endif
                </h2>
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
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('special-tasks.create') }}" class="btn btn-success" id="add_link">
                                    <i class="bi bi-plus"></i> Add
                                </a>
                            </div>
                        </div>
                        <hr>
                        <!-- <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="catFilter" class="mr-2">Commitment Measurable by: </label>
                                    <select id="catFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
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
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="collegeFilter" class="mr-2">College/Branch/Campus/Office where committed: </label>
                                    <select id="collegeFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach($tasks_in_colleges as $college)
                                        <option value="{{ $college->name }}">{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr> -->
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="special_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Commitment Measure</th>
                                        <th>Final Output</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($specialTasks as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id) }}' " >{{ $row->commitment_measure_name }}</td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id) }}' " >{{ $row->final_output }}</td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id) }}' " >{{ $row->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id) }}' " >
                                            {{ $row->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id) }}' " >
                                            {{ $row->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id) }}' " >
                                        <?php
                                            $updated_at = strtotime( $row->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('special-tasks.edit', $row->id) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                <button type="button"  value="{{ $row->id }}" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-request="{{ $row->accomplishment_description }}">Delete</button>
                                                @if ($row->commitment_measure_name == "Quality")
                                                    @if (isset($submissionStatus[30]))
                                                        @if ($submissionStatus[30][$row->id] == 0)
                                                            <a href="{{ url('submissions/check/30/'.$row->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                        @elseif ($submissionStatus[30][$row->id] == 1)
                                                            <a href="{{ url('submissions/check/30/'.$row->id) }}" class="btn btn-sm btn-success">Submitted</a>
                                                        @elseif ($submissionStatus[30][$row->id] == 2)
                                                            <a href="{{ route('special-tasks.edit', $row->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                        @endif 
                                                    @endif 
                                                @elseif ($row->commitment_measure_name == "Efficiency")   
                                                    @if (isset($submissionStatus[31]))
                                                        @if ($submissionStatus[31][$row->id] == 0)
                                                            <a href="{{ url('submissions/check/31/'.$row->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                        @elseif ($submissionStatus[31][$row->id] == 1)
                                                            <a href="{{ url('submissions/check/31/'.$row->id) }}" class="btn btn-sm btn-success">Submitted</a>
                                                        @elseif ($submissionStatus[31][$row->id] == 2)
                                                            <a href="{{ route('special-tasks.edit', $row->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                        @endif   
                                                    @endif   
                                                @elseif ($row->commitment_measure_name == "Timeliness")  
                                                    @if (isset($submissionStatus[32]))
                                                        @if ($submissionStatus[32][$row->id] == 0)
                                                            <a href="{{ url('submissions/check/32/'.$row->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                        @elseif ($submissionStatus[32][$row->id] == 1)
                                                            <a href="{{ url('submissions/check/32/'.$row->id) }}" class="btn btn-sm btn-success">Submitted</a>
                                                        @elseif ($submissionStatus[32][$row->id] == 2)
                                                            <a href="{{ route('special-tasks.edit', $row->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                        @endif   
                                                    @endif   
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
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 4000);

             $('#special_table').DataTable();

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var requestTitle = button.getAttribute('data-bs-request')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = requestTitle

          var url = '{{ route("special-tasks.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;

        });
     </script>
    <script>
        $("#special_table").DataTable();
        //  var table =  $("#special_table").DataTable({
        //     "searchCols": [
        //         null,
        //         null,
        //         null,
        //         null,
        //         { "search": "{{ $currentQuarterYear->current_quarter }}" },
        //         { "search": "{{ $currentQuarterYear->current_year }}" },
        //         null,
        //         null,
        //         null,
        //     ],
        //     initComplete: function () {
        //         this.api().columns(4).every( function () {
        //             var column = this;
        //             var select = $('#quarterFilter')
        //                 .on( 'change', function () {
        //                     var val = $.fn.dataTable.util.escapeRegex(
        //                         $(this).val()
        //                     );

        //                     column
        //                         .search( val ? '^'+val+'$' : '', true, false )
        //                         .draw();
        //                 } );

        //             column.data().unique().sort().each( function ( d, j ) {
        //                 select.append( '<option value="'+d+'">'+d+'</option>' )
        //             } );
        //         });

        //         this.api().columns(5).every( function () {
        //             var column = this;
        //             var select = $('#yearFilter')
        //                 .on( 'change', function () {
        //                     var val = $.fn.dataTable.util.escapeRegex(
        //                         $(this).val()
        //                     );

        //                     column
        //                         .search( val ? '^'+val+'$' : '', true, false )
        //                         .draw();
        //                 } );

        //             column.data().unique().sort().each( function ( d, j ) {
        //                 select.append( '<option value="'+d+'">'+d+'</option>' )
        //             } );
        //         });
        //     }
        //  });

        //   var catIndex = 0;
        //     $("#special_table th").each(function (i) {
        //         if ($($(this)).html() == "Commitment Measurable By:") {
        //             catIndex = i; return false;

        //         }
        //     });

        //     $.fn.dataTable.ext.search.push(
        //         function (settings, data, dataIndex) {
        //             var selectedItem = $('#catFilter').val()
        //             var category = data[catIndex];
        //             if (selectedItem === "" || category.includes(selectedItem)) {
        //                 return true;
        //             }
        //             return false;
        //         }
        //     );

        //     var collegeIndex = 0;
        //     $("#special_table th").each(function (i) {
        //         if ($($(this)).html() == "College/Branch/Campus/Office") {
        //             collegeIndex = i; return false;

        //         }
        //     });

        //     $.fn.dataTable.ext.search.push(
        //         function (settings, data, dataIndex) {
        //             var selectedItem = $('#collegeFilter').val()
        //             var college = data[collegeIndex];
        //             if (selectedItem === "" || college.includes(selectedItem)) {
        //                 return true;
        //             }
        //             return false;
        //         }
        //     );

        //     $("#catFilter").change(function (e) {
        //         table.draw();
        //     });

        //     $("#collegeFilter").change(function (e) {
        //         table.draw();
        //     });

        //     table.draw();
     </script>
     @endpush
</x-app-layout>
