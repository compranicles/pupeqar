<x-app-layout>
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Community Engagements Conducted by College/Department</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if ($message = Session::get('community_success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
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
                                <a href="{{ route('community-engagement.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Community Engagement Conducted by College/Department</a>
                            </div>
                        </div>
                        <hr>
                        <!-- <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quarterFilter" class="mr-2">Quarter Period: </label>
                                    <div class="d-flex">
                                        <select id="quarterFilter" class="custom-select" name="quarter">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="yearFilter" class="mr-2">Year Covered:</label>
                                    <div class="d-flex">
                                        <select id="yearFilter" class="custom-select" name="yearFilter">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                            <table class="table" id="community_engagement_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Active Linkages/ Partnerships covered by MOA</th>
                                        <th>Classification of Agro-industrial Technology</th>
                                        <th>Inclusive Period</th>
                                        <th>Department</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($communityEngagements as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('community-engagement.show', $row->id) }}' ">{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('community-engagement.show', $row->id) }}' ">{{ $row->active_linkages }}</td>
                                        <td onclick="window.location.href = '{{ route('community-engagement.show', $row->id) }}' ">{{ $row->classification_of_agro }}</td>
                                        <td onclick="window.location.href = '{{ route('community-engagement.show', $row->id) }}' ">
                                            <?php
                                            $from = strtotime( $row->from );
                                            $from = date( 'M d, Y', $from );

                                            $to = strtotime( $row->to );
                                            $to = date( 'M d, Y', $to );
                                            ?>
                                            {{ $from }} - {{ $to }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('community-engagement.show', $row->id) }}' ">{{ $row->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('community-engagement.show', $row->id) }}' ">
                                            {{ $row->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('community-engagement.show', $row->id) }}' ">
                                            {{ $row->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('community-engagement.show', $row->id) }}' ">
                                        <?php
                                            $updated_at = strtotime( $row->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('community-engagement.show', $row->id) }}" class="btn btn-sm btn-primary">View</a>
                                                <a href="{{ route('community-engagement.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <button type="button" value="{{ $row->id }}" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-linkages="{{ $row->active_linkages }}">Delete</button>
                                                @if ($submissionStatus[37][$row->id] == 0)
                                                    <a href="{{ url('submissions/check/37/'.$row->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                @elseif ($submissionStatus[37][$row->id] == 1)
                                                    <a href="{{ url('submissions/check/37/'.$row->id) }}" class="btn btn-sm btn-success">Submitted</a>
                                                @elseif ($submissionStatus[37][$row->id] == 2)
                                                    <a href="{{ route('community-engagement.edit', $row->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
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

        $('#community_engagement_table').DataTable();

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var mobilityTitle = button.getAttribute('data-bs-linkages')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = mobilityTitle

          var url = '{{ route("community-engagement.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;

        });
     </script>
     <script>
        // var table =  $("#community_engagement_table").DataTable({
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
        // });

        // var collegeIndex = 0;
        // $("#community_engagement_table th").each(function (i) {
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

        // table.draw();
     </script>
     @endpush
</x-app-layout>
