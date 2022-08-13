<x-app-layout>
    <?php $v = '' ?>
    @if ($version == 'faculty')
        $nameOfPage = 'Special Tasks';
        <?php $v = 'faculty' ?>
    @elseif ($version == 'admin')
        $nameOfPage = 'Accomplishments Based on OPCR';
        <?php $v = 'admin' ?>
    @endif
    @section('title', $nameOfPage ' |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">
                    {{ $nameOfPage }}
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-index">
                        <i class="bi bi-check-circle"></i> {{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('cannot_access'))
                <div class="alert alert-danger alert-index">
                    {{ $message }}
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('special-tasks.create').'?v='.$v }}" class="btn btn-success" id="add_link">
                                    <i class="bi bi-plus"></i> Add
                                </a>
                            </div>
                        </div>
                        <hr>
                        @include('instructions')
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="special_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Commitment Measure</th>
                                        <th>Final Output</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($specialTasks as $row)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id).'?v='.$v }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id).'?v='.$v }}' " >{{ $row->commitment_measure_name }}</td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id).'?v='.$v }}' " >{{ $row->final_output }}</td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id).'?v='.$v }}' " >{{ $row->college_name }}</td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id).'?v='.$v }}' " >
                                            {{ $row->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id).'?v='.$v }}' " >
                                            {{ $row->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('special-tasks.show', $row->id).'?v='.$v }}' " >
                                        <?php
                                            $updated_at = strtotime( $row->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('special-tasks.show', $row->id).'?v='.$v }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">View</a>
                                                <a href="{{ route('special-tasks.edit', $row->id).'?v='.$v }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                <button type="button"  value="{{ $row->id }}" class="btn btn-sm btn-danger d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-request="{{ $row->accomplishment_description }}">Delete</button>
                                                @if ($row->commitment_measure_name == "Quality")
                                                    @if (isset($submissionStatus[30]))
                                                        @if ($submissionStatus[30][$row->id] == 0)
                                                            <a href="{{ url('submissions/check/30/'.$row->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">Submit</a>
                                                        @elseif ($submissionStatus[30][$row->id] == 1)
                                                            <a href="{{ url('submissions/check/30/'.$row->id) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">Submitted</a>
                                                        @elseif ($submissionStatus[30][$row->id] == 2)
                                                            <a href="{{ route('special-tasks.edit', $row->id).'?v='.$v }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                        @endif
                                                    @endif
                                                @elseif ($row->commitment_measure_name == "Efficiency")
                                                    @if (isset($submissionStatus[31]))
                                                        @if ($submissionStatus[31][$row->id] == 0)
                                                            <a href="{{ url('submissions/check/31/'.$row->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">Submit</a>
                                                        @elseif ($submissionStatus[31][$row->id] == 1)
                                                            <a href="{{ url('submissions/check/31/'.$row->id) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">Submitted</a>
                                                        @elseif ($submissionStatus[31][$row->id] == 2)
                                                            <a href="{{ route('special-tasks.edit', $row->id).'?v='.$v }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                        @endif
                                                    @endif
                                                @elseif ($row->commitment_measure_name == "Timeliness")
                                                    @if (isset($submissionStatus[32]))
                                                        @if ($submissionStatus[32][$row->id] == 0)
                                                            <a href="{{ url('submissions/check/32/'.$row->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">Submit</a>
                                                        @elseif ($submissionStatus[32][$row->id] == 1)
                                                            <a href="{{ url('submissions/check/32/'.$row->id) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">Submitted</a>
                                                        @elseif ($submissionStatus[32][$row->id] == 2)
                                                            <a href="{{ route('special-tasks.edit', $row->id).'?v='.$v }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                        @endif
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

        $('#special_table').DataTable();

         //Item to delete to display in delete modal
        var deleteModal = document.getElementById('deleteModal')
        deleteModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var id = button.getAttribute('value')
          var requestTitle = button.getAttribute('data-bs-request')
          var itemToDelete = deleteModal.querySelector('#itemToDelete')
          itemToDelete.textContent = requestTitle

          var url = '{{ route("special-tasks.destroy", ":id") }}';
          url = url.replace(':id', id);
          document.getElementById('delete_item').action = url;

        });
     </script>
     @endpush
</x-app-layout>
