<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="d-flex col-md-12">
                <h5> {{ $user['first_name'].' '.($user['middle_name'] == '' ? '' : $user['middle_name']).' '.$user['last_name'] }}</h5> <p class="ml-3">{{$userRoleNames}}</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="d-flex rounded align-items-center shadow-sm" style="background-color: #820001; padding: 13px;">
                    <div class="ml-2">
                        <i class="bi bi-calendar3 home-icons" style="color: #820001;"></i>
                    </div>
                    <div>
                        <p class="db-text" style="font-size: 1.25em; margin: 0 30px 0 30px;">Quarter {{ isset($currentQuarterYear->current_quarter) ? $currentQuarterYear->current_quarter : '' }} of {{ isset($currentQuarterYear->current_year) ? $currentQuarterYear->current_year : '' }}</p>
                        <?php
                        $deadline = strtotime( $currentQuarterYear->deadline );
                        $deadline = date( 'F d, Y', $deadline);
                        ?>
                        <small style="margin: 0 10px 0 30px; color: whitesmoke;">Deadline: {{ $deadline }}</small>
                    </div>
                </div>
            </div>
            @if (in_array(9, $roles))
                @include('dashboard.superadmin', ['countRegisteredUsers' => $countRegisteredUsers[9]])
            @endif
            @if (in_array(1, $roles) || in_array(3, $roles))
                @include('dashboard.faculty-admin')
            @endif
            @if (in_array(10, $roles))
                @foreach ($department[10] as $value)
                    @include('dashboard.researcher', ['countToReview' => $countToReview[10][$value->college_id], 'collegeCode' => $value->code ])
                @endforeach
            @endif
            @if (in_array(11, $roles))
                @foreach ($department[11] as $value)
                    @include('dashboard.extensionist', ['countToReview' => $countToReview[11][$value->college_id], 'collegeCode' => $value->code ])
                @endforeach
            @endif
            @if (in_array(5, $roles))
                @foreach ($department[5] as $value)
                    @include('dashboard.chairperson', ['countToReview' => $countToReview[5][$value->department_id], 'departmentID' => $value->department_id, 'departmentCode' => $value->code])
                @endforeach
            @endif
            @if (in_array(6, $roles))
                @foreach ($college[6] as $value)
                    @include('dashboard.director', ['countToReview' => $countToReview[6][$value->college_id], 'collegeID' => $value->college_id, 'collegeCode' => $value->code])
                @endforeach
            @endif
            @if (in_array(7, $roles))
                @foreach ($sector[7] as $value)
                    @include('dashboard.sector-head', ['countToReview' => $countToReview[7][$value->sector_id], 'sectorCode' => $value->code])
                    @endforeach
            @endif
            @if (in_array(8, $roles))
                @include('dashboard.ipqmso', ['countToReview' => $countToReview[8], 'countExpectedTotal' => $countExpectedTotal[8], 'countReceived' => $countReceived[8]])
            @endif
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    @if (in_array(8, $roles) || in_array(9, $roles))
                    <div class="col-md-12 mb-4">
                        <div class="card">
                        <h5 class="card-header">Activity Log <small class="ml-2"><a href="{{ route('logs.all') }}" class="home-card-links" style="color: #5b0616;">View all.</a></small></h5>
                            <div class="card-body">
                                <table class="table table-sm table-borderless fixed_header" id="log_activity_table" style="height: 15rem;">
                                    <tbody>
                                    </tbody>
                                    <p class="align-middle text-center" id="no-data-message-admin"></p>
                                </table>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="col-md-12 mb-4">
                        <div class="card">
                        <h5 class="card-header">Recent Activity <small class="ml-2"><a href="{{ route('logs.user') }}" class="home-card-links" style="color: #5b0616;">View all.</a></small></h5>
                            <div class="card-body">
                                <table class="table table-sm table-borderless fixed_header" id="log_activity_individual_table" style="height: 15rem;">
                                    <tbody>
                                    </tbody>
                                    <p class="align-middle text-center" id="no-data-message-indi"></p>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="col-md-12 mb-4">
                        <div class="card">
                        <h5 class="card-header">Add Your Quarterly Accomplishment</h5>
                            <div class="card-body">
                                <h6 class="ml-3 home-titles"><i class="bi bi-book-fill mr-1 home-titles"></i> Academic Program Development</h6>
                                <ul class="home-report-list">
                                    @can('viewAny', \App\Models\Syllabus::class)
                                    <li><a href="{{ route('syllabus.create') }}">Course Syllabus</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\Reference::class)
                                    <li><a href="{{ route('rtmmi.create') }}">Reference/Textbook/Module/Monographs/Instructional Materials</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\StudentAward::class)
                                    <li><a href="{{ route('student-award.create') }}">Student Awards and Recognition (Department/College Level)</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\StudentTraining::class)
                                    <li><a href="{{ route('student-training.create') }}">Student Attended Seminars and Trainings (Department/College Level)</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\ViableProject::class)
                                    <li><a href="{{ route('viable-project.create') }}">Viable Demonstration Projects (Department/College Level)</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\CollegeDepartmentAward::class)
                                    <li><a href="{{ route('college-department-award.create') }}">Awards and Recognition Received by the Department/College or Office</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\TechnicalExtension::class)
                                    <li><a href="{{ route('technical-extension.create') }}">Technical Extension Programs/ Projects/ Activities (Department/College Level)</a></li>
                                    @endcan
                                </ul>
                                <h6 class="ml-3 home-titles"><i class="bi bi-people-fill home-titles mr-1"></i> Extension Programs & Expert Services</h6>
                                <ul class="home-report-list">
                                    @can('viewAny', \App\Models\ExpertServiceConsultant::class)
                                    <li><a href="{{ route('expert-service-as-consultant.create') }}">Expert Service Rendered as Consultant</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\ExpertServiceConference::class)
                                    <li><a href="{{ route('expert-service-in-conference.create') }}">Expert Service Rendered in Conference, Workshops, and/or Training Course for Professional</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\ExpertServiceAcademic::class)
                                    <li><a href="{{ route('expert-service-in-academic.create') }}">Expert Service Rendered in Academic Journals/Books/Publication/Newsletter/Creative Works</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\ExtensionService::class)
                                    <li><a href="{{ route('extension-service.create') }}">Extension Services</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\Partnership::class)
                                    <li><a href="{{ route('partnership.create') }}">Partnership/Linkages/Network</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\Mobility::class)
                                    <li><a href="{{ route('mobility.create') }}">Inter-country Mobility</a></li>
                                    @endcan
                                    @can('manage', \App\Models\IntraMobility::class)
                                    <li><a href="{{ route('intra-mobility.create') }}">Intra-country Mobility</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\OutreachProgram::class)
                                    <li><a href="{{ route('outreach-program.create') }}">Community Relation and Outreach Program (Department/College Level)</a></li>
                                    @endcan
                                </ul>
                                <h6 class="ml-3 home-titles"><i class="bi bi-person-circle home-titles mr-1"></i> Personal Data</h6>
                                <ul class="home-report-list">
                                    <li><a href="{{ route('submissions.officership.index') }}">Officerships/ Memberships</a></li>
                                    <li><a href="{{ route('submissions.educ.index') }}">Ongoing Studies</a></li>
                                    <li><a href="{{ route('submissions.award.index') }}">Outstanding Awards</a></li>
                                    <li><a href="{{ route('submissions.development.index') }}">Seminars and Trainings</a></li>
                                </ul>
                                <h6 class="ml-3 home-titles"><i class="bi bi-search home-titles mr-1"></i> Research & Invention</h6>
                                <ul class="home-report-list">
                                    @can('viewAny', \App\Models\Research::class)
                                    <li><a href="{{ route('research.create') }}">Research Registration</a></li>
                                    @endcan
                                    @can('viewAny', \App\Models\Invention::class)
                                    <li><a href="{{ route('invention-innovation-creative.create') }}">Inventions, Innovation, & Creativity</a></li>
                                    @endcan
                                </ul>
                                @can('viewAny', \App\Models\Request::class)
                                <h6 class="ml-3 home-titles"><i class="bi bi-info-circle-fill home-titles mr-1"></i> Requests & Queries</h6>
                                <ul class="home-report-list">
                                    <li><a href="{{ route('request.create') }}">Requests and Queries Acted Upon</a></li>
                                </ul>
                                @endcan
                                <h6 class="ml-3 home-titles"><i class="bi bi-bookmark-star-fill home-titles mr-1"></i> Tasks & Functions</h6>
                                <ul class="home-report-list">
                                    @can('manage', \App\Models\SpecialTask::class)
                                    <li><a href="{{ route('special-tasks.create') }}">Academic Special Tasks</a></li>
                                    @endcan
                                    @can('manage', \App\Models\AdminSpecialTask::class)
                                    @admin
                                    <li><a href="{{ route('admin-special-tasks.create') }}">&#8226; Admin Special Tasks</a></li>
                                    @endadmin
                                    @endcan
                                    @admin
                                    <li><a href="{{ route('special-tasks.create') }}">Accomplishments Based on OPCR</a></li>
                                    @endadmin
                                    @can('manage', \App\Models\AttendanceFunction::class)
                                    <li><a href="{{ route('attendance-function.index') }}">Attendance in College & University Functions</a></li>
                                    @endcan
                                </ul>
                                <h6 class="ml-3 home-titles"><i class="bi bi-file-plus-fill home-titles mr-1"></i> Others</h6>
                                <ul class="home-report-list">
                                    @can('manage', \App\Models\OtherAccomplishment::class)
                                    <li><a href="{{ route('other-accomplishment.create') }}">Other Individual Accomplishments</a></li>
                                    @endcan
                                    @can('manage', \App\Models\OtherDeptAccomplishment::class)
                                    <li><a href="{{ route('other-dept-accomplishment.create') }}">Other Department/College Accomplishments</a></li>
                                    @endcan
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="card">
                        <h5 class="card-header">Announcements</h5>
                            <div class="card-body">
                                <table style="background-color: white;">
                                    @php $i = 0; @endphp
                                    @forelse($announcements as $announcement)
                                    <thead>
                                        <tr>
                                            <th role="button" class="font-weight-bold home-titles" style="{{ $i == 0 ? ' ' : 'border-top: 1px solid #dee2e6; padding-top: 10px;' }}"
                                                    data-bs-toggle="modal" data-bs-target="#announcementModal" data-bs-subject="{{ $announcement->subject }}"
                                                    data-bs-message="{{ $announcement->message }}" data-bs-date="{{ date( 'F j, Y', strtotime($announcement->updated_at)) }}">
                                                <small style="color: var(--gray-dark)">{{ date( "F j, Y", strtotime($announcement->updated_at)) }}</small>
                                                <br>
                                                {{$announcement->subject}}
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="home-report-list" style="padding-bottom: 10px;"><small>{{substr_replace($announcement->message, "...", 100)}}</small></td>
                                        </tr>
                                    </tbody>
                                    @php $i++; @endphp
                                    @empty
                                        <p class="align-middle text-center">No announcements to show.</p>
                                    @endforelse
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="announcementModal" tabindex="-1" aria-labelledby="announcementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="announcementModalLabel">Announcement</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center">
                        <div class="col-md-11">

                            <h5 class="modal-subject font-weight-bold">Announcement</h5>
                            <h6 class="modal-date"></h6>
                            <br>
                            <p id="message"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        //Announcement to display in modal
        var announcementModal = document.getElementById('announcementModal')
        announcementModal.addEventListener('show.bs.modal', function (event) {
          var button = event.relatedTarget
          var subject = button.getAttribute('data-bs-subject')
          var date = button.getAttribute('data-bs-date')
          var message = button.getAttribute('data-bs-message')
          var modalTitle = announcementModal.querySelector('.modal-subject')
          var modalSubTitle = announcementModal.querySelector('.modal-date')
          var modalBody = announcementModal.querySelector('#message')
          modalTitle.textContent = subject
          modalSubTitle.textContent = "Date Announced: " + date
          modalBody.textContent = message
        });
    </script>
    <script>
        $(function(){
            getLog();
            getLogInd();

            setInterval(getLog, 60000);
            setInterval(getLogInd, 60000);
        });

        function getLog(){
            $('.activity-log-content').remove();

            $.get('{{ url("/get-dashboard-list") }}', function (data){
                var countColumns = 0;

                if (data.length == 0) {
                    $('#no-data-message-admin').text('No recent logs to show.');
                } else
                    $("#no-data-message-admin").css("display", "none");
                data.forEach(function(item){
                    $('#log_activity_table').append('<tr id="activity-log-'+countColumns+'" class="activity-log-content"></tr>');
                    $('#activity-log-'+countColumns)
                        .append('<td class="activity-log-content text-small border-bottom"><i class="bi bi-square-fill mr-2" style="color: #820001;"></i>'+
                                item.subject
                            +'<div class="text-muted ml-4"><small>'+item.name+' &#183; '+item.created_at+'</small></div></td>'
                        );
                    countColumns++;
                });
            });
        }
        function getLogInd(){
            $('.activity-log-indi-content').remove();

            $.get('{{ url("/get-dashboard-list-indi") }}', function (data){
                var countColumns = 0;

                if (data.length == 0) {
                    $('#no-data-message-indi').text('No recent activities to show.');
                } else
                    $("#no-data-message-indi").css("display", "none");
                data.forEach(function(item){
                    $('#log_activity_individual_table').append('<tr id="activity-log-indi-'+countColumns+'" class=" activity-log-indi-content"></tr>');
                    $('#activity-log-indi-'+countColumns)
                        .append('<td class="activity-log-indi-content text-small border-bottom"><i class="bi bi-square-fill mr-2" style="color: #820001;"></i>'+
                                item.subject
                            +'<div class="text-muted ml-4"><small>'+item.created_at+'</small></div></td>'
                        );
                    countColumns++;
                });
            });
        }
    </script>
    @endpush
</x-app-layout>
