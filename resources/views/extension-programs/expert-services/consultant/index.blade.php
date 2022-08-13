<x-app-layout>
    @section('title', 'Expert Services Rendered as Consultant |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mb-2">Expert Service Rendered as Consultant</h3>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if ($message = Session::get('edit_esconsultant_success'))
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
                                <a href="{{ route('expert-service-as-consultant.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Expert Service as Consultant</a>
                            </div>
                        </div>
                        <hr>
                        @include('instructions')
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="esconsultant_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Classification</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($expertServicesConsultant as $expertServiceConsultant)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' ">{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' ">{{ $expertServiceConsultant->title }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' ">{{ $expertServiceConsultant->classification_name }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' ">{{ $expertServiceConsultant->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' ">
                                             {{ $expertServiceConsultant->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' ">
                                            {{ $expertServiceConsultant->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' ">
                                        <?php
                                            $updated_at = strtotime( $expertServiceConsultant->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('expert-service-as-consultant.show', $expertServiceConsultant) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">View</a>
                                                <a href="{{ route('expert-service-as-consultant.edit', $expertServiceConsultant) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                <button type="button" value="{{ $expertServiceConsultant->id }}" class="btn btn-sm btn-danger d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-esconsultant="{{ $expertServiceConsultant->title }}">Delete</button>
                                                @if ($submissionStatus[9][$expertServiceConsultant->id] == 0)
                                                    <a href="{{ url('submissions/check/9/'.$expertServiceConsultant->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">Submit</a>
                                                @elseif ($submissionStatus[9][$expertServiceConsultant->id] == 1)
                                                    <a href="{{ url('submissions/check/9/'.$expertServiceConsultant->id) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">Submitted</a>
                                                @elseif ($submissionStatus[9][$expertServiceConsultant->id] == 2)
                                                    <a href="{{ route('expert-service-as-consultant.edit', $expertServiceConsultant->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
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

        $('#esconsultant_table').DataTable();

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var esConsultantTitle = button.getAttribute('data-bs-esconsultant')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = esConsultantTitle

          var url = '{{ route("expert-service-as-consultant.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;
        });
     </script>
     @endpush
</x-app-layout>
