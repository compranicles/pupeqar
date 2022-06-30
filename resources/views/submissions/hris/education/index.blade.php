<x-app-layout>
    @section('title', 'Ongoing Advanced/Professional Studies |')
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
                                <h3>Ongoing Advanced/Professional Studies</h3>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                @include('instructions')
                                <div class="table-responsive">
                                    <table class="table table-hover" id="education_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>School Name</th>
                                                <th>Level</th>
                                                <th>Inclusive Dates of Attendance</th>
                                                <th>Action</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($educationFinal as $education)
                                                @if ($education->IsCurrentlyEnrolled == 'Y')
                                                    <tr>
                                                        <td>{{ $loop->iteration }}</td>
                                                        <td>{{ $education->SchoolName }}</td>
                                                        <td>
                                                            @foreach ( $educationLevel as $level)
                                                                @if ($level->EducationLevelID == $education->EducationLevelID)
                                                                    {{ $level->EducationLevel }}
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                        <td>
                                                            {{ $education->IncDate }}
                                                        </td>
                                                        <td>
                                                            @if ($educReports != null)
                                                                @foreach ($educReports as $educReport)
                                                                    @if ($educReport->report_reference_id == $education->EmployeeEducationBackgroundID)
                                                                        <a class="text-primary h4"><i class="fas fa-plus"></i> Add</a>
                                                                        @break
                                                                    @else
                                                                    <a href="{{ route('submissions.educ.add', $education->EmployeeEducationBackgroundID) }}" class="text-primary h4"><i class="fas fa-plus"></i> Add</a>
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($educReports != null)
                                                                @foreach ($educReports as $educReport)
                                                                    @if ($educReport->report_reference_id == $education->EmployeeEducationBackgroundID)
                                                                        <span class="badge bg-success">Submitted</span>
                                                                        <span class="badge bg-secondary">Quarter {{ $educReport->report_quarter.' of '. $educReport->report_year}}</span>
                                                                        @break
                                                                    @endif
                                                                @endforeach
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endif
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
                $('#education_table').DataTable({
                });
            } );
            // auto hide alert
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 4000);
        </script>
    @endpush

</x-app-layout>
