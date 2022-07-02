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
                                                <th>Status</th>
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
                                                        @if ($seminarReports != null)
                                                            @foreach ($seminarReports as $seminarReport)
                                                                @if ($seminarReport->report_reference_id == $development->EmployeeTrainingProgramID)
                                                                    <a class="btn btn-sm btn-primary mb-2">Add as Seminar</a>
                                                                    @break
                                                                @else
                                                                    <a href="{{ route('submissions.development.seminar.add', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-primary mb-2">Add as Seminar</a>
                                                                    @break
                                                                @endif
                                                            @endforeach
                                                        @endif

                                                        @if ($trainingReports != null)
                                                            @foreach ($trainingReports as $trainingReport)
                                                                @if ($trainingReport->report_reference_id == $development->EmployeeTrainingProgramID)
                                                                    <a class="btn btn-sm btn-primary">Add as Training</a>
                                                                    @break
                                                                @else
                                                                    <a href="{{ route('submissions.development.training.add', $development->EmployeeTrainingProgramID) }}" class="btn btn-sm btn-primary">Add as Training</a>
                                                                    @break
                                                                @endif
                                                            @endforeach
                                                        @endif


                                                    </td>
                                                    <td class="text-center">

                                                    @if ($seminarReports != null)
                                                        @foreach ($seminarReports as $seminarReport)
                                                            @if ($seminarReport->report_reference_id == $development->EmployeeTrainingProgramID)
                                                                <span class="badge bg-success">Submitted as seminar</span>
                                                                <span class="badge bg-secondary">Quarter {{ $seminarReport->report_quarter.' of '. $seminarReport->report_year}}</span>
                                                                @break
                                                            @endif
                                                        @endforeach
                                                    @endif
<!--  -->
                                                    @if ($trainingReports != null)
                                                        @foreach ($trainingReports as $trainingReport)
                                                            @if ($trainingReport->report_reference_id == $development->EmployeeTrainingProgramID)
                                                                <span class="badge bg-success">Submitted as training</span>
                                                                <span class="badge bg-secondary">Quarter {{ $trainingReport->report_quarter.' of '. $trainingReport->report_year}}</span>
                                                                @break
                                                            @endif
                                                        @endforeach
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
                $(".alert-index").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);
        </script>
    @endpush

</x-app-layout>
