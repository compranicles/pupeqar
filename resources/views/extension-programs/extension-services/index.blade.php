<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Extension Services') }}
        </h2>
    </x-slot>
    @php
    $currentMonth = date('m');

    $year_or_quarter = 0;
    if ($currentMonth <= 3 && $currentMonth >= 1) 
        $quarter = 1;
    if ($currentMonth <= 6 && $currentMonth >= 4)
        $quarter = 2;
    if ($currentMonth <= 9 && $currentMonth >= 7)
        $quarter = 3;
    if ($currentMonth <= 12 && $currentMonth >= 10) 
        $quarter = 4;
    @endphp
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                @if ($message = Session::get('edit_eservice_success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>            
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('extension-service.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Extension Service</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="row">
                                <div class="col-md-3">
                                    <label for="statusFilter" class="mr-2">Current Status: </label>
                                    <select id="statusFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach ($status as $row)
                                        <option value="{{ $row->name }}">{{ $row->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="quarterFilter" class="mr-2">Quarter Period (Year <?php echo date('Y'); ?>): </label>
                                    <div class="d-flex">
                                        <select id="quarterFilter" class="custom-select" name="quarter">
                                            <option value="1" {{$quarter== 1 ? 'selected' : ''}} class="quarter">1</option>
                                            <option value="2" {{$quarter== 2 ? 'selected' : ''}} class="quarter">2</option>
                                            <option value="3" {{$quarter== 3 ? 'selected' : ''}} class="quarter">3</option>
                                            <option value="4" {{$quarter== 4 ? 'selected' : ''}} class="quarter">4</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="collegeFilter" class="mr-2">College/Branch/Campus/Office where committed: </label>
                                    <select id="collegeFilter" class="custom-select">
                                        <option value="">Show All</option>
                                        @foreach($eservice_in_colleges as $college)
                                        <option value="{{ $college->name }}">{{ $college->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="eservice_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
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
                                        <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' ">{{ $extensionService->quarter }}</td>

                                        <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' ">
                                            <?php $updated_at = strtotime( $extensionService->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>
                                            {{ $updated_at }} </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('extension-service.edit', $extensionService) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square" style="font-size: 1.25em;"></i></a>
                                                <button type="button" value="{{ $extensionService->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-eservice="{{ ($extensionService->title_of_extension_program != null ? $extensionService->title_of_extension_program : ($extensionService->title_of_extension_project != null ? $extensionService->title_of_extension_project : ($extensionService->title_of_extension_activity != null ? $extensionService->title_of_extension_activity : ''))) }}"><i class="bi bi-trash" style="font-size: 1.25em;"></i></button>
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
             $('#eservice_table').DataTable();
         } );

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
         var table =  $("#eservice_table").DataTable();

          var statusIndex = 0;
            $("#eservice_table th").each(function (i) {
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

            var quarterIndex = 0;
            $("#eservice_table th").each(function (i) {
                if ($($(this)).html() == "Quarter") {
                    quarterIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#quarterFilter').val()
                    var quarter = data[quarterIndex];
                    if (selectedItem === "" || quarter.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            var collegeIndex = 0;
            $("#eservice_table th").each(function (i) {
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
            $("#quarterFilter").change(function (e) {
                table.draw();
            });

            $("#collegeFilter").change(function (e) {
                table.draw();
            });

            table.draw();
     </script>
     @endpush
</x-app-layout>