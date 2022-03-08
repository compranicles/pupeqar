<x-app-layout>
    <x-slot name="header">
        @include('submissions.hris.navigation')
    </x-slot>

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
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Trainings and Seminars</h3>
                                <hr>
                            </div>
                            <div class="col-md-12">
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
                                                    <td class="text-center">
                                                        @if ($development->ClassificationID == '0')
                                                            <a href="{{ route('submissions.development.seminar.add', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-primary m-1">Add as Seminar</a>
                                                            <a href="{{ route('submissions.development.training.add', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-primary">Add as Training</a>
                                                        @elseif ($development->ClassificationID >= '1' && $development->ClassificationID <= '4')
                                                            <a href="{{ route('submissions.development.seminar.add', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-primary m-1">Add as Seminar</a>
                                                        @elseif ($development->ClassificationID >= '1' && $development->ClassificationID <= '4')
                                                            <a href="{{ route('submissions.development.training.add', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-primary">Add as Training</a>
                                                        @endif
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
                $(".temp-alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 4000);
        </script>
    @endpush

</x-app-layout>