<div class="menu-sub animate slideIn">
    <div class="row d-flex justify-content-start align-items-baseline">
        @notpureadmin
        <div class="col-md-3">
            <ul>
                <h6 class="menu-category">ACADEMIC PROGRAM DEVELOPMENT</h6>
                @can('viewAny', \App\Models\Syllabus::class)
                <li><a class="{{ request()->routeIs('syllabus.*') ? 'active' : '' }}" href="{{ route('syllabus.index') }}">&#8226; Course Syllabus</a></li>
                @endcan
                @can('viewAny', \App\Models\Reference::class)
                <li><a class="{{ request()->routeIs('rtmmi.*') ? 'active' : '' }}" href="{{ route('rtmmi.index') }}">&#8226; Reference/Textbook/Module/<br> Monographs/Instructional Materials</a></li>
                @endcan
                @can('viewAny', \App\Models\StudentAward::class)
                <li><a class="{{ request()->routeIs('student-award.*') ? 'active' : '' }}" href="{{ route('student-award.index') }}">&#8226; Student Awards and Recognition</a></li>
                @endcan
                @can('viewAny', \App\Models\StudentTraining::class)
                <li><a class="{{ request()->routeIs('student-training.*') ? 'active' : '' }}" href="{{ route('student-training.index') }}">&#8226; Student Attended Seminars and Trainings</a></li>
                @endcan
                {{-- For College and Departments --}}
                <li><a class="{{ request()->routeIs('community-engagement.*') ? 'active' : '' }}" href="{{ route('community-engagement.index') }}">&#8226; Community Engagement Conducted by College/Department</a></li>
                @can('viewAny', \App\Models\ViableProject::class)
                <li><a class="{{ request()->routeIs('viable-project.*') ? 'active' : '' }}" href="{{ route('viable-project.index') }}">&#8226; Viable Demonstration Projects</a></li>
                @endcan
            </ul>
        </div>
        @endnotpureadmin
        <div class="col-md-3">
            <ul>
                <h6 class="menu-category">EXTENSION PROGRAMS & EXPERT SERVICES</h6>
                @can('viewAny', \App\Models\ExpertServiceConsultant::class)
                <li><a class="{{ request()->routeIs('expert-service-as-consultant.*') ? 'active' : '' }}" href="{{ route('expert-service-as-consultant.index') }}">&#8226; Expert Service Rendered as Consultant</a></li>
                <li><a class="{{ request()->routeIs('expert-service-in-conference.*') ? 'active' : '' }}" href="{{ route('expert-service-in-conference.index') }}">&#8226; Expert Service Rendered in Conference, Workshops, and/or Training Course for Professional</a></li>
                <li><a class="{{ request()->routeIs('expert-service-in-academic.*') ? 'active' : '' }}" href="{{ route('expert-service-in-academic.index') }}">&#8226; Expert Service Rendered in Academic Journals/Books/Publication/Newsletter/ Creative Works</a></li>
                @endcan
                @can('viewAny', \App\Models\ExtensionService::class)
                <li><a class="{{ request()->routeIs('extension-service.*') ? 'active' : '' }}" href="{{ route('extension-service.index') }}">&#8226; Extension Program/Project/Activity</a></li>
                @endcan
                @can('viewAny', \App\Models\Mobility::class)
                <li><a class="{{ request()->routeIs('mobility.*') ? 'active' : '' }}" href="{{ route('mobility.index') }}">&#8226; Inter-country Mobility</a></li>
                @endcan
                <li><a class="{{ request()->routeIs('intra-mobility.*') ? 'active' : '' }}" href="{{ route('intra-mobility.index') }}">&#8226; Intra-country Mobility</a></li>
                @can('viewAny', \App\Models\Partnership::class)
                <li><a class="{{ request()->routeIs('partnership.*') ? 'active' : '' }}" href="{{ route('partnership.index') }}">&#8226; Partnership/Linkages/Network</a></li>
                @endcan
            </ul>
        </div>
        <div class="col-md-3">
            <ul>
                {{-- For College and Departments --}}
                <li><a class="{{ request()->routeIs('community-engagement.*') ? 'active' : '' }}" href="{{ route('community-engagement.index') }}">&#8226; Community Engagement Conducted by College/Department</a></li>
                @can('viewAny', \App\Models\OutreachProgram::class)
                <li><a class="{{ request()->routeIs('outreach-program.*') ? 'active' : '' }}" href="{{ route('outreach-program.index') }}">&#8226; Community Relation and Outreach Program</a></li>
                @endcan
                @can('viewAny', \App\Models\TechnicalExtension::class)
                <li><a class="{{ request()->routeIs('technical-extension.*') ? 'active' : '' }}" href="{{ route('technical-extension.index') }}">&#8226; Technical Extension Program/Project/Activity</a></li>
                @endcan
            </ul>
            @can('viewAny', \App\Models\Invention::class)
            <ul>
                <h6 class="menu-category">INVENTIONS, INNOVATION & CREATIVITY</h6>
                <li><a class="{{ request()->routeIs('invention-innovation-creative.*') ? 'active' : '' }}" href="{{ route('invention-innovation-creative.index') }}">&#8226; Inventions, Innovation, and Creative Works</a></li>
            </ul>
            @endcan
            <ul>
                <h6 class="menu-category">PERSONAL DATA</h6>
                <li><a href="{{ route('submissions.officership.index') }}" class="{{ request()->routeIs('submissions.officership.*') ? 'active' : '' }} ">&#8226; Officerships/ Memberships</a></li>
                <li><a href="{{ route('submissions.educ.index') }}" class="{{ request()->routeIs('submissions.educ.*') ? 'active' : '' }} ">&#8226; Ongoing Studies</a></li>
                <li><a href="{{ route('submissions.award.index') }}" class="{{ request()->routeIs('submissions.award.*') ? 'active' : '' }} ">&#8226; Outstanding Awards</a></li>
                <li><a href="{{ route('submissions.development.index') }}" class="{{ request()->routeIs('submissions.development.*') ? 'active' : '' }}">&#8226; Seminars and Trainings</a></li>
            </ul>
        </div>
        <div class="col-md-3">
            @can('viewAny', \App\Models\Research::class)
            <ul>
                <h6 class="menu-category">RESEARCH & BOOK CHAPTER</h6>
                <li><a class="{{ request()->routeIs('research.*') ? 'active' : '' }}" href="{{ route('research.index') }}">&#8226; Research Registration</a></li>
            </ul>
            @endcan
            @can('viewAny', \App\Models\Request::class)
            <ul>
                <h6 class="menu-category">REQUESTS & QUERIES</h6>
                <li><a class="{{ request()->routeIs('request.*') ? 'active' : '' }}" href="{{ route('request.index') }}">&#8226; Requests and Queries Acted Upon</a></li>
            </ul>
            @endcan
            <ul>
                <h6 class="menu-category">OTHERS</h6>
                <li><a class="{{ request()->routeIs('other-accomplishment.*') ? 'active' : '' }}" href="{{ route('other-accomplishment.index') }}">&#8226; Other Individual Accomplishments</a></li>
                <li><a class="{{ request()->routeIs('other-dept-accomplishment.*') ? 'active' : '' }}" href="{{ route('other-dept-accomplishment.index') }}">&#8226; Other Department/College Accomplishments</a></li>
            </ul>
        </div>
    </div>
</div>