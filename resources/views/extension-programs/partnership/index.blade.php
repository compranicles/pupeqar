<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Partnership, Linkages & Network') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                @if ($message = Session::get('partnership_success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i>  {{ $message }}
                </div>          
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('partnership.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Partnership, Linkages & Network</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="partnership_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>MOA/MOU Code</th>
                                        <th>Title</th>
                                        <th>Name of Organization/ Partner</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($partnerships as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' " >{{ $row->moa_code }}</td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' " >{{ $row->title_of_partnership }}</td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' " >{{ $row->name_of_partner }}</td>
                                        <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' " >
                                            <?php $updated_at = strtotime( $row->updated_at );
                                                $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>  
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('partnership.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                                <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-partnership="{{ $row->title_of_partnership }}"><i class="bi bi-trash"></i> Delete</button>
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
             $('#partnership_table').DataTable();
         } );

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var partnershipTitle = button.getAttribute('data-bs-partnership')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = partnershipTitle

          var url = '{{ route("partnership.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
          
        });
     </script>
     @endpush
</x-app-layout>