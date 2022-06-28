<x-app-layout>

        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Technical Extension Programs/Projects/Activities</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if ($message = Session::get('extension_success'))
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
                                <a href="{{ route('technical-extension.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Technical Extension Programs/ Projects/ Activities</a>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="yearFilter" class="mr-2">Year Added:</label>
                                    <div class="d-flex">
                                        <select id="yearFilter" class="custom-select" name="yearFilter">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr> -->
                        <div class="table-responsive">
                            <table class="table" id="technical_extension_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Name of the Adoptor</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($technical_extensions as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('technical-extension.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('technical-extension.show', $row->id) }}' " >{{ ($row->program_title != null ? $row->program_title : ($row->project_title != null ? $row->project_title : ($row->activity_title != null ? $row->activity_title : ''))) }}</td>
                                        <td onclick="window.location.href = '{{ route('technical-extension.show', $row->id) }}' " >{{ $row->name_of_adoptor }}</td>
                                        <td onclick="window.location.href = '{{ route('technical-extension.show', $row->id) }}' " >
                                            {{ $row->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('technical-extension.show', $row->id) }}' " >
                                            {{ $row->report_year }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('technical-extension.edit', $row->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                                <button type="button" value="{{ $row->id }}" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-project="{{ $row->name }}">Delete</button>
                                                @if ($submissionStatus[23][$row->id] == 0)
                                                    <a href="{{ url('submissions/check/23/'.$row->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                @elseif ($submissionStatus[23][$row->id] == 1)
                                                    <a href="{{ url('submissions/check/23/'.$row->id) }}" class="btn btn-sm btn-success">Submitted</a>
                                                @elseif ($submissionStatus[23][$row->id] == 2)
                                                    <a href="{{ route('technical-extension.edit', $row->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
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

        $('#technical_extension_table').DataTable();

        //  $(document).ready( function () {
        //     var table = $('#technical_extension_table').DataTable({
        //         "searchCols": [
        //             null,
        //             null,
        //             { "search": "{{ $currentQuarterYear->current_quarter }}" },
        //             { "search": "{{ $currentQuarterYear->current_year }}" },
        //             null
        //         ],
        //         initComplete: function () {
        //             this.api().columns(2).every( function () {
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

        //             this.api().columns(3).every( function () {
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
            var rtmmiTitle = button.getAttribute('data-bs-extension')
            var itemToDelete = deleteModal.querySelector('#itemToDelete')
            itemToDelete.textContent = rtmmiTitle

            var url = '{{ route("technical-extension.destroy", ":id") }}';
            url = url.replace(':id', id);
            document.getElementById('delete_item').action = url;
        });
     </script>
     @endpush
</x-app-layout>
