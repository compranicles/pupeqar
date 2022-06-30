<x-app-layout>
        @section('title', 'Expert Services Rendered in Rendered in Academic Works |')
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mb-2">Expert Services Rendered in Academic Journals/ Books/ Publication/ Newsletter/ Creative Works</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if ($message = Session::get('edit_esacademic_success'))
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
                                <a href="{{ route('expert-service-in-academic.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Expert Service in Academic Journals, Books, Publication, Newsletter, or Creative Works</a>
                            </div>
                        </div>
                        <hr>
                        @include('instructions')
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="esacademic_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Publication/ Audio Visual Production</th>
                                        <th>Classification</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expertServicesAcademic as $expertServiceAcademic)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' ">{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' ">{{ $expertServiceAcademic->publication_or_audio_visual }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' ">{{ $expertServiceAcademic->classification }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' ">{{ $expertServiceAcademic->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' ">
                                            {{ $expertServiceAcademic->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' ">
                                            {{ $expertServiceAcademic->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' ">
                                        <?php
                                            $updated_at = strtotime( $expertServiceAcademic->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('expert-service-in-academic.show', $expertServiceAcademic) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">View</a>
                                                <a href="{{ route('expert-service-in-academic.edit', $expertServiceAcademic) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                <button type="button" value="{{ $expertServiceAcademic->id }}" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-esacademic="{{ $expertServiceAcademic->publication_or_audio_visual }}">Delete</button>
                                                @if ($submissionStatus[11][$expertServiceAcademic->id] == 0)
                                                    <a href="{{ url('submissions/check/11/'.$expertServiceAcademic->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                @elseif ($submissionStatus[11][$expertServiceAcademic->id] == 1)
                                                    <a href="{{ url('submissions/check/11/'.$expertServiceAcademic->id) }}" class="btn btn-sm btn-success">Submitted</a>
                                                @elseif ($submissionStatus[11][$expertServiceAcademic->id] == 2)
                                                    <a href="{{ route('expert-service-in-academic.edit', $expertServiceAcademic->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
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
            $(".alert-index").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 4000);

        $('#esacademic_table').DataTable();

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var esAcademicTitle = button.getAttribute('data-bs-esacademic')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = esAcademicTitle

          var url = '{{ route("expert-service-in-academic.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;

        });
     </script>
     @endpush
</x-app-layout>
