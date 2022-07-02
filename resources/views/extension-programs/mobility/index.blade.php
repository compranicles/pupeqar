<x-app-layout>
        @section('title', 'Inter-Country Mobility |')
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Inter-Country Mobility</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if ($message = Session::get('mobility_success'))
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
                                <a href="{{ route('mobility.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Inter-Country Mobility</a>
                            </div>
                        </div>
                        <hr>
                        @include('instructions')
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="mobility_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Description</th>
                                        <th>Hosting Institution/<br>Organization/Agency</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($mobilities as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' ">{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' ">{{ $row->mobility_description }}</td>
                                        <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' ">{{ $row->host_name }}</td>
                                        <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' ">{{ $row->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' ">
                                            {{ $row->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' ">
                                            {{ $row->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' ">
                                        <?php
                                            $updated_at = strtotime( $row->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('mobility.show', $row->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">View</a>
                                                <a href="{{ route('mobility.edit', $row->id) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                <button type="button" value="{{ $row->id }}" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-mobility="{{ $row->host_name }}">Delete</button>
                                                @if($row->classification_of_person == '298')
                                                    @if ($submissionStatus[35][$row->id] == 0)
                                                        <a href="{{ url('submissions/check/35/'.$row->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                    @elseif ($submissionStatus[35][$row->id] == 1)
                                                        <a href="{{ url('submissions/check/35/'.$row->id) }}" class="btn btn-sm btn-success">Submitted</a>
                                                    @elseif ($submissionStatus[35][$row->id] == 2)
                                                        <a href="{{ route('mobility.edit', $row->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                    @endif
                                                @else
                                                    @if ($submissionStatus[14][$row->id] == 0)
                                                        <a href="{{ url('submissions/check/14/'.$row->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                    @elseif ($submissionStatus[14][$row->id] == 1)
                                                        <a href="{{ url('submissions/check/14/'.$row->id) }}" class="btn btn-sm btn-success">Submitted</a>
                                                    @elseif ($submissionStatus[14][$row->id] == 2)
                                                        <a href="{{ route('mobility.edit', $row->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                    @endif
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

        $('#mobility_table').DataTable();

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
