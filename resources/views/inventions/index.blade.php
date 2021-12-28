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
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('invention-innovation-creative.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Invention, Innovation, or Creative Work</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="row">
                            <div class="d-flex mr-2">
                                <div class="col-md-6">
                                    <label for="statusFilter" class="mr-2">Current Status: </label>
                                    <select id="statusFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach ($inventionStatus as $status)
                                        <option value="{{ $status->name }}">{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="collegeFilter" class="mr-2">College/Branch/Office where committed: </label>
                                    <select id="collegeFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach($iicw_in_colleges as $college)
                                        <option value="{{ $college->name }}">{{ $college->name }}</option>
                                        @endforeach
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
                                        <th>Classification</th>
                                        <th>Status</th>
                                        <th>College/Branch/Office</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventions as $invention)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">{{ $invention->title }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' " id="classification-iicw-{{$invention->id}}" ></td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">{{ $invention->status_name }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">{{ $invention->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' ">
                                            <?php $updated_at = strtotime( $invention->updated_at );
                                                $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>  
                                            {{ $updated_at }}
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
         var table =  $("#invention_table").DataTable();
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
                if ($($(this)).html() == "College/Branch/Office") {
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