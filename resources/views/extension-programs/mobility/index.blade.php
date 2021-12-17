<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Inter-Country Mobility') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                @if ($message = Session::get('mobility_success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>             
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('mobility.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Inter-Country Mobility</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="mobility_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Hosting Institution/ Organization/ Agency</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mobilities as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' " >{{ $row->host_name }}</td>
                                        <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' " >{{ $row->updated_at }}</td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('mobility.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                                <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-mobility="{{ $row->host_name }}"><i class="bi bi-trash"></i> Delete</button>
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
             $('#mobility_table').DataTable();
         } );

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var mobilityTitle = button.getAttribute('data-bs-mobility')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = mobilityTitle

          var url = '{{ route("mobility.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
          
        });
     </script>
     @endpush
</x-app-layout>