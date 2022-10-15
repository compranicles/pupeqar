<x-app-layout>
    <div class="container">
        @section('title', 'RTMMI |')
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Reference, Textbook, Module, Monographs & Instructional Materials</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                @if (($accomplishment = Session::get('edit_rtmmi_success')) && ($action = Session::get('action')) )
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $accomplishment }} has been {{$action}}
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
                        <div class="mb-3">
                            <div class="d-inline mr-2">
                                <a href="{{ route('rtmmi.create') }}" class="btn btn-success"><i class="bi bi-plus"></i> Add Reference, Textbook, Module, Monograph, or Instructional Material</a>
                            </div>
                        </div>
                        <hr>
                        @include('instructions')
                        <div class="table-responsive" style="overflow-x:auto;">
                            <table class="table" id="rtmmi_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>College/Branch/Campus/Office</th>
                                        <th>Quarter</th>
                                        <th>Year</th>
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
                                        <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >{{ $rtmmi->college_name }}</td>
                                        <td class="{{ ($rtmmi->report_quarter == $currentQuarterYear->current_quarter && $rtmmi->report_year == $currentQuarterYear->current_year) ? 'to-submit' : '' }}" onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >
                                            {{ $rtmmi->report_quarter }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >
                                            {{ $rtmmi->report_year }}
                                        </td>
                                        <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >
                                        <?php
                                            $updated_at = strtotime( $rtmmi->updated_at );
                                            $updated_at = date( 'M d, Y h:i A', $updated_at );
                                            ?>
                                            {{ $updated_at }}
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group" aria-label="button-group">
                                                <a href="{{ route('rtmmi.show', $rtmmi->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">View</a>
                                                <a href="{{ route('rtmmi.edit', $rtmmi->id) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                <button type="button"  value="{{ $rtmmi->id }}" class="btn btn-sm btn-danger d-inline-flex align-items-center" value="{{ $rtmmi->id }}" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-rtmmi="{{ $rtmmi->title }}">Delete</button>
                                                    @if ($submissionStatus[15][$rtmmi->id] == 0)
                                                        <a href="{{ url('submissions/check/15/'.$rtmmi->id) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">Submit</a>
                                                    @elseif ($submissionStatus[15][$rtmmi->id] == 1)
                                                        <a href="{{ url('submissions/check/15/'.$rtmmi->id) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">Submitted {{ $submitRole[$rtmmi->id] == 'f' ? 'as Faculty' : 'as Admin' }}</a>
                                                    @elseif ($submissionStatus[15][$rtmmi->id] == 2)
                                                        <a href="{{ route('rtmmi.edit', $rtmmi->id) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
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

        $('#rtmmi_table').DataTable();

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
