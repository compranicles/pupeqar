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
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('expert-service-in-conference.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Expert Service in Conference/Workshop/Training Course</a>
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
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expertServicesConference as $expertServiceConference)
                                    <tr class="tr-hover" role="button">
                                        <td><a href="{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}"></a>{{ $loop->iteration }}</td>
                                        <td>{{ $expertServiceConference->title }}</td>
                                        <td>{{ $expertServiceConference->nature }}</td>
                                        <td>
                                            <?php $updated_at = strtotime( $expertServiceConference->updated_at );
                                                $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>        
                                            {{ $updated_at }}
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
         $('#esconference_table').on('click', 'tbody td', function(){
                window.location = $(this).closest('tr').find('td:eq(0) a').attr('href');
            });
     </script>
     @endpush
</x-app-layout>