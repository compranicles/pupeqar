<x-app-layout>
    <!-- <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Course Syllabus') }}
        </h2>
    </x-slot> -->

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
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('syllabus.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Course Syllabus</a>
                            </div>
                        </div>  
                        <hr>
                        <!-- <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="taskFilter" class="mr-2">Assigned Task: </label>
                                    <select id="taskFilter" class="custom-select">
                                        <option value="">Show All</option>
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
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="finishFilter" class="mr-2">Year Finished: <span style="color:red;">*</span></label>
                                    <div class="d-flex">
                                        <select id="finishFilter" class="custom-select yearFilter" name="finishFilter">
                                            <option value="finished" {{ $year == "finished" ? 'selected' : '' }} class="present_year">--</option>
                                            @foreach($syllabiYearsFinished as $syllabiFinished)
                                            <option value="{{ $syllabiFinished->finished }}" {{ $year == $syllabiFinished->finished ? 'selected' : ''}}>{{ $syllabiFinished->finished }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <small><span style="color:red;">*</span> Selects all records filtered by year.</small>
                        <hr> -->
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
                                        <td>
                                        <?php
                                            $updated_at = strtotime( $syllabus->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at ); 
                                            ?>  
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('syllabus.edit', $syllabus->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <button type="button"  value="{{ $syllabus->id }}" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-syllabus="{{ $syllabus->course_title }}">Delete</button>
                                                <button type="button" class="btn btn-sm btn-success">Submit</button>
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
     <script>
        //  var table =  $("#syllabus_table").DataTable({
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
        //         this.api().columns(2).every( function () {
        //             var column = this;
        //             var select = $('#taskFilter')
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

        //         this.api().columns(3).every( function () {
        //             var column = this;
        //             var select = $('#collegeFilter')
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

        //   var taskIndex = 0;
        //     $("#syllabus_table th").each(function (i) {
        //         if ($($(this)).html() == "Assigned Task") {
        //             taskIndex = i; return false;

        //         }
        //     });

        //     $.fn.dataTable.ext.search.push(
        //         function (settings, data, dataIndex) {
        //             var selectedItem = $('#taskFilter').val()
        //             var task = data[taskIndex];
        //             if (selectedItem === "" || task.includes(selectedItem)) {
        //                 return true;
        //             }
        //             return false;
        //         }
        //     );

        //     var collegeIndex = 0;
        //     $("#syllabus_table th").each(function (i) {
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


        //     $("#taskFilter").change(function (e) {
        //         table.draw();
        //     });
            
        //     $("#collegeFilter").change(function (e) {
        //         table.draw();
        //     });

        //     table.draw();
     </script>
    <script>
        
    </script>
    <!-- <script>
        $('#finishFilter').on('change', function () {
            var year = $('#finishFilter').val();
            var link = "/academic-development/syllabus/:year/:filter";
            var newLink = link.replace(':year', year).replace(':filter', "finished");
            window.location.replace(newLink);
        });
    </script> -->
     @endpush
</x-app-layout>