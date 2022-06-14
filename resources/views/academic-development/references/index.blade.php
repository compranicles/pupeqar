<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Reference, Textbook, Module, Monographs, and Instructional Materials') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @if (($accomplishment = Session::get('edit_rtmmi_success')) && ($action = Session::get('action')) )
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $accomplishment }} has been {{$action}}
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
                                <a href="{{ route('rtmmi.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Reference, Textbook, Module, Monograph, or Instructional Material</a>
                            </div>
                        </div>  
                        <hr>
                        <!-- <div class="row my-auto">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="catFilter" class="mr-2">Category: </label>
                                    <select id="catFilter" class="custom-select">
                                        <option value="">Show All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
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
                            <div class="col-md-5">
                                <div class="form-group m-0">
                                    <label for="collegeFilter" class="mr-2">College/Branch/Campus/Office where committed: </label>
                                    <select id="collegeFilter" class="custom-select">
                                        <option value="">Show All</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr> -->
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="rtmmi_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Added</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allRtmmi as $rtmmi)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >{{ $rtmmi->title }}</td>
                                        <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >{{ $rtmmi->category_name }}</td>
                                        <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >{{ $rtmmi->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >
                                            {{ $rtmmi->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >
                                            {{ $rtmmi->report_year }}
                                        </td>
                                        <td>
                                            <?php 
                                            $created_at = strtotime( $rtmmi->created_at );
                                            $created_at = date( 'M d, Y h:i A', $created_at );
                                            ?>
                                            {{ $created_at }}
                                        </td>
                                        <td>
                                        <?php
                                            $updated_at = strtotime( $rtmmi->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at ); 
                                            ?>  
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('rtmmi.edit', $rtmmi->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                                <button type="button" value="{{ $rtmmi->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-rtmmi="{{ $rtmmi->title }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
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

        $('#rtmmi_table').DataTable();

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var rtmmiTitle = button.getAttribute('data-bs-rtmmi')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = rtmmiTitle

          var url = '{{ route("rtmmi.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
          
        });
     </script>
     <!-- <script>
         var table =  $("#rtmmi_table").DataTable({
            "searchCols": [
                null,
                null,
                null,
                null,
                // { "search": "{{ $currentQuarterYear->current_quarter }}" },
                // { "search": "{{ $currentQuarterYear->current_year }}" },
                null,
                null,
                null,
                null,
                null,
            ],
            initComplete: function () {
                this.api().columns(2).every( function () {
                    var column = this;
                    var select = $('#catFilter')
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
                            if ("{{ $currentQuarterYear->current_quarter }}" == d)
                                select.append( '<option value="'+d+'" selected>'+d+'</option>' )
                            else
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
                        if ("{{ $currentQuarterYear->current_year }}" == d)
                            select.append( '<option value="'+d+'" selected>'+d+'</option>' )
                        else
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                    } );
                });
            }
         });
         var catIndex = 0;
            $("#rtmmi_table th").each(function (i) {
                if ($($(this)).html() == "Category") {
                    catIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#catFilter').val()
                    var category = data[catIndex];
                    if (selectedItem === "" || category.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

         var collegeIndex = 0;
            $("#rtmmi_table th").each(function (i) {
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

            $("#catFilter").change(function (e) {
                table.draw();
            });

            $("#collegeFilter").change(function (e) {
                table.draw();
            });

            table.draw();
            
     </script> -->
     @endpush
</x-app-layout>