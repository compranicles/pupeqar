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
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('rtmmi.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Reference, Textbook, Module, Monograph, or Instructional Material</a>
                            </div>
                        </div>  
                        <hr>
                        <div class="table-responsive">
                            <table class="table" id="rtmmi_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Category</th>
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
                                        <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >
                                            <?php $updated_at = strtotime( $rtmmi->updated_at );
                                                $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>  
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('rtmmi.edit', $rtmmi->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                                <button type="button" value="{{ $rtmmi->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-rtmmi="{{ $rtmmi->title }}"><i class="bi bi-trash"></i> Delete</button>
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
             $('#rtmmi_table').DataTable();
         } );

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
     @endpush
</x-app-layout>