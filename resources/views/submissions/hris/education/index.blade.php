<x-app-layout>
    <x-slot name="header">
        @include('submissions.navigation')
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('submissions.hris.navigation-bar')
            </div>
        </div>
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h3>Ongoing Advanced/Professional Studies</h3>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="education_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>School Name</th>
                                                <th>Level</th>
                                                <th>Inclusive Dates of Attendance</th>
                                                <th>Action</th>
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
                                                            <a href="{{ route('submissions.educ.add', $education->EmployeeEducationBackgroundID) }}" class="text-primary h4"><i class="fas fa-plus"></i></i></a>
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
                $(".temp-alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 4000);
        </script>
    @endpush

</x-app-layout>