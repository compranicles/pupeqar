<x-app-layout>
    @section('title', 'Seminars & Trainings |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        {{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @endif
                @if ($message = Session::get('cannot_access'))
                    <div class="alert alert-danger">
                        {{ $message }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Seminars & Trainings</h3>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                @include('instructions')
                                <div class="table-responsive">
                                    <table class="table table-hover" id="development_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Title</th>
                                                <th>Inclusive Date</th>
                                                <th>Level</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($developmentFinal as $development)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $development->TrainingProgram }}</td>
                                                    <td>{{ $development->IncDate }}</td>
                                                    <td>{{ $development->Level }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="button-group">
                                                            @if(in_array($development->EmployeeTrainingProgramID, $savedSeminars)||in_array($development->EmployeeTrainingProgramID, $savedTrainings))
                                                                <a href="{{ route('submissions.development.show', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">View</a>
                                                                <a href="{{ route('submissions.development.edit', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                                <!-- <button type="button" value="{{ $development->EmployeeTrainingProgramID }}" class="btn btn-sm btn-danger d-inline-flex align-items-center" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-development="{{ $development->TrainingProgram }}">Delete</button> -->
                                                                @if(isset($submissionStatus[25]))
                                                                    @if(isset($submissionStatus[25][$development->EmployeeTrainingProgramID]))
                                                                        @if ($submissionStatus[25][$development->EmployeeTrainingProgramID] == 0 )
                                                                            <a href="{{ route('submissions.development.check', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">Submit</a>
                                                                        @elseif ($submissionStatus[25][$development->EmployeeTrainingProgramID] == 1 )
                                                                            <a href="{{ route('submissions.development.check', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">Submitted</a>
                                                                        @elseif ($submissionStatus[25][$development->EmployeeTrainingProgramID] == 2 )
                                                                            <a href="{{ route('submissions.development.edit', $development->EmployeeTrainingProgramID ) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                                @if(isset($submissionStatus[26]))
                                                                    @if(isset($submissionStatus[26][$development->EmployeeTrainingProgramID]))
                                                                        @if ( $submissionStatus[26][$development->EmployeeTrainingProgramID] == 0)
                                                                            <a href="{{ route('submissions.development.check', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-primary d-inline-flex align-items-center">Submit</a>
                                                                        @elseif ($submissionStatus[26][$development->EmployeeTrainingProgramID] == 1)
                                                                            <a href="{{ route('submissions.development.check', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">Submitted</a>
                                                                        @elseif ($submissionStatus[26][$development->EmployeeTrainingProgramID] == 2)
                                                                            <a href="{{ route('submissions.development.edit', $development->EmployeeTrainingProgramID ) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                                        @endif
                                                                    @endif
                                                                @endif
                                                            @else
                                                                <a href="{{ route('submissions.development.add', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-success d-inline-flex align-items-center">Add</a>
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
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="text-center">Are you sure you want to delete this accomplishment?</h5>
                <p id="itemToDelete" class="text-center h4"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-bs-dismiss="modal">Cancel</button>
                <a href="" type="button" class="btn btn-danger mb-2 mr-2" id="delete_modal">Delete</a>
                </form>
            </div>
        </div>
    </div>


    @push('scripts')
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
        <script>

            $(document).ready( function () {
                $('#development_table').DataTable({
                });
            } );
            // auto hide alert
            window.setTimeout(function() {
                $(".alert-index").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);

             //Item to delete to display in delete modal
            var deleteModal = document.getElementById('deleteModal')
            deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            var id = button.getAttribute('value')
            var developmentTitle = button.getAttribute('data-bs-development')
            var itemToDelete = deleteModal.querySelector('#itemToDelete')
            itemToDelete.textContent = developmentTitle

            var url = '{{ route("submissions.development.destroy", ":id") }}';
            url = url.replace(':id', id);
            $('#delete_modal').attr('href', url);
            });
        </script>
    @endpush

</x-app-layout>
