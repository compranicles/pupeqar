<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Invention, Innovation & Creative Works') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                @if ($message = Session::get('edit_iicw_success'))
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
                                <a href="{{ route('invention-innovation-creative.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Invention, Innovation, or Creative Work</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="row">
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
                                    <label for="createFilter" class="mr-2">Year Added: </label>
                                    <select id="createFilter" class="custom-select">
                                    </select>
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
                        <div class="row">
                            <div class="col-12">
                                <hr>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table" id="invention_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventions as $invention)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">{{ $invention->title }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">{{ $invention->status_name }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">{{ $invention->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">
                                            {{ $invention->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">
                                            {{ $invention->report_year }}
                                        </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('invention-innovation-creative.edit', $invention->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                                <button type="button" value="{{ $invention->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-iicw="{{ $invention->title }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
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
             $('#invention_table').DataTable();
         } );

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var iicwTitle = button.getAttribute('data-bs-iicw')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = iicwTitle

          var url = '{{ route("invention-innovation-creative.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
          
        });
     </script>
     <script>
        var table =  $("#invention_table").DataTable({
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
                this.api().columns(2).every( function () {
                    var column = this;
                    var select = $('#statusFilter')
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

                this.api().columns(3).every( function () {
                    var column = this;
                    var select = $('#collegeFilter')
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
                    var select = $('#createFilter')
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

        var statusIndex = 0;
        $("#invention_table th").each(function (i) {
            if ($($(this)).html() == "Status") {
                statusIndex = i; return false;

            }
        });

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var selectedItem = $('#statusFilter').val()
                var status = data[statusIndex];
                if (selectedItem === "" || status.includes(selectedItem)) {
                    return true;
                }
                return false;
            }
        );

        var collegeIndex = 0;
        $("#invention_table th").each(function (i) {
            if ($($(this)).html() == "College/Branch/Campus/Office") {
                collegeIndex = i; return false;

            }
        });

        $.fn.dataTable.ext.search.push(
            function (settings, data, dataIndex) {
                var selectedItem = $('#collegeFilter').val()
                var college = data[collegeIndex];
                if (selectedItem === "" || college.includes(selectedItem)) {
                    return true;
                }
                return false;
            }
        );

        $("#statusFilter").change(function (e) {
            table.draw();
        });

        $("#collegeFilter").change(function (e) {
            table.draw();
        });

        table.draw();
     </script>
     @endpush
</x-app-layout>