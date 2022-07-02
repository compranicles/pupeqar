<x-app-layout>
    @section('title', 'Other Department/College Accomplishments |')
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Other Department/College Accomplishments</h2>
            </div>
        </div>
        <div class="row">

            <div class="col-lg-12">
                @if ($message = Session::get('other_dept_success'))
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
                                <a href="{{ route('other-dept-accomplishment.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Other Department/College Accomplishment</a>
                            </div>
                        </div>
                        <hr>
                        @include('instructions')
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="other_accomplishment_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Description</th>
                                        <th>Date</th>
                                        <th>Level</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($otherAccomplishments as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('other-dept-accomplishment.show', $row->id) }}' ">{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('other-dept-accomplishment.show', $row->id) }}' ">{{ $row->accomplishment_description }}</td>
                                        <td onclick="window.location.href = '{{ route('other-dept-accomplishment.show', $row->id) }}' ">
                                            <?php
                                                $from = strtotime( $row->from );
                                                $from = date( 'F d, Y', $from );

                                                if ($row->to != null) {
                                                    $to = strtotime( $row->to );
                                                    $to = date( 'F d, Y', $to );
                                                } else
                                                    $to = '';
                                            ?>
                                            {{ $from }} - {{ $to }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('other-dept-accomplishment.show', $row->id) }}' ">{{ $row->accomplishment_level }}</td>
                                        <td onclick="window.location.href = '{{ route('other-dept-accomplishment.show', $row->id) }}' ">
                                            {{ $row->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('other-dept-accomplishment.show', $row->id) }}' ">
                                            {{ $row->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('other-dept-accomplishment.show', $row->id) }}' ">
                                        <?php
                                            $updated_at = strtotime( $row->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('other-dept-accomplishment.show', $row->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">View</a>
                                                <a href="{{ route('other-dept-accomplishment.edit', $row->id) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                <button type="button" value="{{ $row->id }}" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-description="{{ $row->accomplishment_description }}">Delete</button>
                                                @if ($submissionStatus[39][$row->id] == 0)
                                                    <a href="{{ url('submissions/check/39/'.$row->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                @elseif ($submissionStatus[39][$row->id] == 1)
                                                    <a href="{{ url('submissions/check/39/'.$row->id) }}" class="btn btn-sm btn-success">Submitted</a>
                                                @elseif ($submissionStatus[39][$row->id] == 2)
                                                    <a href="{{ route('other-dept-accomplishment.edit', $row->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
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

        $('#other_accomplishment_table').DataTable();

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var mobilityTitle = button.getAttribute('data-bs-description')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = mobilityTitle

          var url = '{{ route("other-dept-accomplishment.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;

        });
     </script>
     @endpush
</x-app-layout>
