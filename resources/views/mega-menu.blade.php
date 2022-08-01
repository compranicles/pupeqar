<div class="menu-sub animate slideIn">
    <div class="row d-flex justify-content-start align-items-baseline">
        @notpureadmin
        <div class="col-md-3">
            <ul>
                <h6 class="menu-category">ACADEMIC PROGRAM DEVELOPMENT</h6>
                @can('viewAny', \App\Models\Syllabus::class)
                <li><a class="{{ request()->routeIs('syllabus.*') ? 'active' : '' }}" href="{{ route('syllabus.index') }}">Course Syllabus</a></li>
                @endcan
                @can('viewAny', \App\Models\Reference::class)
                <li><a class="{{ request()->routeIs('rtmmi.*') ? 'active' : '' }}" href="{{ route('rtmmi.index') }}">Reference/Textbook/Module/<br> Monographs/Instructional Materials</a></li>
                @endcan
                @can('viewAny', \App\Models\StudentAward::class)
                <li><a class="{{ request()->routeIs('student-award.*') ? 'active' : '' }}" href="{{ route('student-award.index') }}">Student Awards and Recognition</a></li>
                @endcan
                @can('viewAny', \App\Models\StudentTraining::class)
                <li><a class="{{ request()->routeIs('student-training.*') ? 'active' : '' }}" href="{{ route('student-training.index') }}">Student Attended Seminars and Trainings</a></li>
                @endcan
                {{-- For College and Departments --}}
                @can('viewAny', \App\Models\CollegeDepartmentAward::class)
                <li><a class="{{ request()->routeIs('college-department-award.*') ? 'active' : '' }}" href="{{ route('college-department-award.index') }}">Awards and Recognition Received by the College/Department</a></li>
                @endcan
                @can('viewAny', \App\Models\ViableProject::class)
                <li><a class="{{ request()->routeIs('viable-project.*') ? 'active' : '' }}" href="{{ route('viable-project.index') }}">Viable Demonstration Projects</a></li>
                @endcan
            </ul>
        </div>
        @endnotpureadmin
        <div class="col-md-3">
            <ul>
                <h6 class="menu-category">EXTENSION PROGRAMS & EXPERT SERVICES</h6>
                @can('viewAny', \App\Models\ExpertServiceConsultant::class)
                <li><a class="{{ request()->routeIs('expert-service-as-consultant.*') ? 'active' : '' }}" href="{{ route('expert-service-as-consultant.index') }}">Expert Service Rendered as Consultant</a></li>
                <li><a class="{{ request()->routeIs('expert-service-in-conference.*') ? 'active' : '' }}" href="{{ route('expert-service-in-conference.index') }}">Expert Service Rendered in Conference, Workshops, and/or Training Course for Professional</a></li>
                <li><a class="{{ request()->routeIs('expert-service-in-academic.*') ? 'active' : '' }}" href="{{ route('expert-service-in-academic.index') }}">Expert Service Rendered in Academic Journals/ Books/Publication/ Newsletter/ Creative Works</a></li>
                @endcan
                @can('viewAny', \App\Models\ExtensionService::class)
                <li><a class="{{ request()->routeIs('extension-service.*') ? 'active' : '' }}" href="{{ route('extension-service.index') }}">Extension Program/Project/Activity</a></li>
                @endcan
                @can('viewAny', \App\Models\Mobility::class)
                <li><a class="{{ request()->routeIs('mobility.*') ? 'active' : '' }}" href="{{ route('mobility.index') }}">Inter-country Mobility</a></li>
                @endcan
                @can('manage', \App\Models\IntraMobility::class)
                <li><a class="{{ request()->routeIs('intra-mobility.*') ? 'active' : '' }}" href="{{ route('intra-mobility.index') }}">Intra-country Mobility</a></li>
                @endcan
                @can('viewAny', \App\Models\Partnership::class)
                <li><a class="{{ request()->routeIs('partnership.*') ? 'active' : '' }}" href="{{ route('partnership.index') }}">Partnership/Linkages/Network</a></li>
                @endcan
            </ul>
        </div>
        <div class="col-md-3">
            <ul>
                {{-- For College and Departments --}}
                @can('manage', \App\Models\CommunityEngagement::class)
                <li><a class="{{ request()->routeIs('community-engagement.*') ? 'active' : '' }}" href="{{ route('community-engagement.index') }}">Community Engagement Conducted by College/Department</a></li>
                @endcan
                @can('viewAny', \App\Models\OutreachProgram::class)
                <li><a class="{{ request()->routeIs('outreach-program.*') ? 'active' : '' }}" href="{{ route('outreach-program.index') }}">Community Relation and Outreach Program</a></li>
                @endcan
                @can('viewAny', \App\Models\TechnicalExtension::class)
                <li><a class="{{ request()->routeIs('technical-extension.*') ? 'active' : '' }}" href="{{ route('technical-extension.index') }}">Technical Extension Program/Project/Activity</a></li>
                @endcan
            </ul>
            @can('viewAny', \App\Models\Invention::class)
            <ul>
                <h6 class="menu-category">INVENTIONS, INNOVATION & CREATIVITY</h6>
                <li><a class="{{ request()->routeIs('invention-innovation-creative.*') ? 'active' : '' }}" href="{{ route('invention-innovation-creative.index') }}">Inventions, Innovation, and Creative Works</a></li>
            </ul>
            @endcan
            <ul>
                <h6 class="menu-category">PERSONAL DATA</h6>
                <li><a href="{{ route('submissions.officership.index') }}" class="{{ request()->routeIs('submissions.officership.*') ? 'active' : '' }} ">Officerships/ Memberships</a></li>
                <li><a href="{{ route('submissions.educ.index') }}" class="{{ request()->routeIs('submissions.educ.*') ? 'active' : '' }} ">Ongoing Studies</a></li>
                <li><a href="{{ route('submissions.award.index') }}" class="{{ request()->routeIs('submissions.award.*') ? 'active' : '' }} ">Outstanding Awards</a></li>
                <li><a href="{{ route('submissions.development.index') }}" class="{{ request()->routeIs('submissions.development.*') ? 'active' : '' }}">Seminars and Trainings</a></li>
            </ul>
        </div>
        <div class="col-md-3">
            @can('viewAny', \App\Models\Research::class)
            <ul>
                <h6 class="menu-category">RESEARCH & BOOK CHAPTER</h6>
                <li><a class="{{ request()->routeIs('research.*') ? 'active' : '' }}" href="{{ route('research.index') }}">Research Registration</a></li>
            </ul>
            @endcan
            @can('viewAny', \App\Models\Request::class)
            <ul>
                <h6 class="menu-category">REQUESTS & QUERIES</h6>
                <li><a class="{{ request()->routeIs('request.*') ? 'active' : '' }}" href="{{ route('request.index') }}">Requests and Queries Acted Upon</a></li>
            </ul>
            @endcan
            <ul>
                <h6 class="menu-category">TASKS & FUNCTIONS</h6>
                @can('manage', \App\Models\SpecialTask::class)
                <li><a class="{{ request()->routeIs('special-tasks.*')  ? 'active' : ''  }}" href="{{ route('special-tasks.index').'?v=faculty' }}">Academic Special Tasks</a></li>
                @endcan
                @can('manage', \App\Models\AdminSpecialTask::class)
                @admin
                <li><a class="{{ request()->routeIs('admin-special-tasks.*') ? 'active' : '' }}" href="{{ route('admin-special-tasks.index') }}">Admin Special Tasks</a></li>
                @endadmin
                @endcan
                @admin
                <li><a class="{{ request()->routeIs('special-tasks.*') ? 'active' : ''  }}" href="{{ route('special-tasks.index').'?v=admin' }}">Accomplishments Based on OPCR</a></li>
                @endadmin
                @can('manage', \App\Models\AttendanceFunction::class)
                <li><a class="{{ request()->routeIs('attendance-function.*') ? 'active' : '' }}" href="{{ route('attendance-function.index') }}">Attendance in College & University Functions</a></li>
                @endcan
            </ul>
            <ul>
                <h6 class="menu-category">OTHERS</h6>
                @can('manage', \App\Models\OtherAccomplishment::class)
                <li><a class="{{ request()->routeIs('other-accomplishment.*') ? 'active' : '' }}" href="{{ route('other-accomplishment.index') }}">Other Individual Accomplishments</a></li>
                @endcan
                @can('manage', \App\Models\OtherDeptAccomplishment::class)
                <li><a class="{{ request()->routeIs('other-dept-accomplishment.*') ? 'active' : '' }}" href="{{ route('other-dept-accomplishment.index') }}">Other Department/College Accomplishments</a></li>
                @endcan
            </ul>
        </div>
    </div>
</div>
