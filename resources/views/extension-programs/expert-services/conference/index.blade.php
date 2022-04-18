<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Expert Services Rendered') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
            @include('extension-programs.navigation-bar')
            </div>

            <div class="col-lg-12">
                @if ($message = Session::get('edit_esconference_success'))
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
                                <a href="{{ route('expert-service-in-conference.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Expert Service in Conference/Workshop/Training Course</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="natureFilter" class="mr-2">Nature: </label>
                                        <select id="natureFilter" class="custom-select">
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
                                        <label for="yearFilter" class="mr-2">Year Added:</label>
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
                        <div class="table-responsive">
                            <table class="table" id="esconference_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Nature</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expertServicesConference as $expertServiceConference)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">{{ $expertServiceConference->title }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">{{ $expertServiceConference->nature }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">{{ $expertServiceConference->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">
                                            {{ $expertServiceConference->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' ">
                                            {{ $expertServiceConference->report_year }}
                                        </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('expert-service-in-conference.edit', $expertServiceConference) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                                <button type="button" value="{{ $expertServiceConference->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-esconference="{{ $expertServiceConference->title }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
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
             $('#esconference_table').DataTable();
         } );

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var esConferenceTitle = button.getAttribute('data-bs-esconference')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = esConferenceTitle

          var url = '{{ route("expert-service-in-conference.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
          
        });
     </script>
     <script>
         var table =  $("#esconference_table").DataTable({
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
                    var select = $('#natureFilter')
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
          var natureIndex = 0;
            $("#esconference_table th").each(function (i) {
                if ($($(this)).html() == "Nature") {
                    natureIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#natureFilter').val()
                    var nature = data[natureIndex];
                    if (selectedItem === "" || nature.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );
            
            var collegeIndex = 0;
            $("#esconference_table th").each(function (i) {
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

            $("#natureFilter").change(function (e) {
                table.draw();
            });
           
            $("#collegeFilter").change(function (e) {
                table.draw();
            });

            table.draw();
     </script>
     @endpush
</x-app-layout>