<x-app-layout>
    <!-- <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Extension Programs/Projects/Activities') }}
        </h2>
    </x-slot> -->

    <div class="row">
        <div class="col-md-12">
            <h2 class="font-weight-bold mb-2">Extension Programs/Projects/Activities</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            @if ($message = Session::get('edit_eservice_success'))
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
                            <a href="{{ route('extension-service.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Extension Program/Project/Activity</a>
                        </div>
                        <button class="btn btn-primary mr-1" data-toggle="modal" data-target="#invitesModal">
                            Extensions to Add (Shared by your Partner) @if (count($invites) != 0)
                                        <span class="badge badge-secondary">{{ count($invites) }}</span>
                                    @else
                                        <span class="badge badge-secondary">0</span>
                                    @endif
                        </button>
                    </div>
                    <hr>
                    <!-- <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="statusFilter" class="mr-2">Current Status: </label>
                                <select id="statusFilter" class="custom-select">
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
                    <hr> -->
                    <div class="table-responsive" style="overflow-x:auto;">
                        <table class="table" id="eservice_table">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Title</th>
                                    <th>Status</th>
                                    <th>College/Branch/Campus/Office</th>
                                    <th>Quarter</th>
                                    <th>Year</th>
                                    <th>Date Modified</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($extensionServices as $extensionService)
                                <tr class="tr-hover" role="button">
                                    <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' ">{{ $loop->iteration }}</td>
                                    <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' ">{{ ($extensionService->title_of_extension_program != null ? $extensionService->title_of_extension_program : ($extensionService->title_of_extension_project != null ? $extensionService->title_of_extension_project : ($extensionService->title_of_extension_activity != null ? $extensionService->title_of_extension_activity : ''))) }}</td>
                                    <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' ">{{ $extensionService->status }}</td>
                                    <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' ">{{ $extensionService->college_name }}</td>
                                    <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' ">
                                        {{ $extensionService->report_quarter }}
                                    </td>
                                    <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' ">
                                        {{ $extensionService->report_year }}
                                    </td>
                                    <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' ">
                                    <?php
                                        $updated_at = strtotime( $extensionService->updated_at );
                                        $updated_at = date( 'M d, Y h:i A', $updated_at );
                                        ?>
                                        {{ $updated_at }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group" aria-label="button-group">
                                            <a href="{{ route('extension-service.show', $extensionService) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">View</a>
                                            <a href="{{ route('extension-service.edit', $extensionService) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                            <button type="button" value="{{ $extensionService->id }}" class="btn btn-sm btn-danger d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-eservice="{{ ($extensionService->title_of_extension_program != null ? $extensionService->title_of_extension_program : ($extensionService->title_of_extension_project != null ? $extensionService->title_of_extension_project : ($extensionService->title_of_extension_activity != null ? $extensionService->title_of_extension_activity : ''))) }}">Delete</button>
                                            @if ($submissionStatus[12][$extensionService->id] == 0)
                                                <a href="{{ url('submissions/check/12/'.$extensionService->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">Submit</a>
                                            @elseif ($submissionStatus[12][$extensionService->id] == 1)
                                                <a href="{{ url('submissions/check/12/'.$extensionService->id) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">Submitted</a>
                                            @elseif ($submissionStatus[12][$extensionService->id] == 2)
                                                <a href="{{ route('extension-service.edit', $extensionService->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
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
    @include('extension-programs.extension-services.invite.modal', compact('invites'))

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

             $('#eservice_table').DataTable();

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var eServiceTitle = button.getAttribute('data-bs-eservice')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = eServiceTitle

          var url = '{{ route("extension-service.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;

        });
     </script>
     <script>
        //  var table =  $("#eservice_table").DataTable({
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
        //             var select = $('#statusFilter')
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

        //   var statusIndex = 0;
        //     $("#eservice_table th").each(function (i) {
        //         if ($($(this)).html() == "Status") {
        //             statusIndex = i; return false;

        //         }
        //     });

        //     $.fn.dataTable.ext.search.push(
        //         function (settings, data, dataIndex) {
        //             var selectedItem = $('#statusFilter').val()
        //             var status = data[statusIndex];
        //             if (selectedItem === "" || status.includes(selectedItem)) {
        //                 return true;
        //             }
        //             return false;
        //         }
        //     );

        //     var collegeIndex = 0;
        //     $("#eservice_table th").each(function (i) {
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

        //     $("#statusFilter").change(function (e) {
        //         table.draw();
        //     });

        //     $("#collegeFilter").change(function (e) {
        //         table.draw();
        //     });

        //     table.draw();
     </script>
     @endpush
</x-app-layout>
