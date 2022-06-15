<div class="menu-sub animate slideIn">
    <div class="row">
        <!-- <div class="col-md-4">
            <ul>
                
                <h6 class="menu-category">Personnel</h6>
                <li><a class="{{ request()->routeIs('expert-service-in-academic.*') ? 'active' : '' }}" href="">Professional Study</a></li>
            </ul>
            <ul>
                <h6 class="menu-category">Employee Development</h6>
                <li><a class="{{ request()->routeIs('expert-service-in-academic.*') ? 'active' : '' }}" href="">Awards & Recognition</a></li>
                <li><a class="{{ request()->routeIs('expert-service-in-academic.*') ? 'active' : '' }}" href="">Officership/Membership</a></li>
                <li><a class="{{ request()->routeIs('expert-service-in-academic.*') ? 'active' : '' }}" href="">Seminars</a></li>
                <li><a class="{{ request()->routeIs('expert-service-in-academic.*') ? 'active' : '' }}" href="">Trainings</a></li>
            </ul>
            
        </div> -->
        
        <div class="col-md-4">
            <ul>
                <h6 class="menu-category">Academic Program Development</h6>
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
                @can('viewAny', \App\Models\ViableProject::class)
                <li><a class="{{ request()->routeIs('viable-project.*') ? 'active' : '' }}" href="{{ route('viable-project.index') }}">Viable Demonstration Projects</a></li>
                @endcan
                @can('viewAny', \App\Models\CollegeDepartmentAward::class)
                @director
                <li><a class="{{ request()->routeIs('college-department-award.*') ? 'active' : '' }}" href="{{ route('college-department-award.index') }}">Awards and Recognition Received<br> by the College/Branch/Campus</a></li>
                @enddirector
                @chairperson
                <li><a class="{{ request()->routeIs('college-department-award.*') ? 'active' : '' }}" href="{{ route('college-department-award.index') }}">Awards and Recognition Received<br> by the Department</a></li>
                @endchairperson
                @endcan
            </ul>
            
        </div>
        <div class="col-md-4">
            <ul>
                <h6 class="menu-category">Extension Programs & Expert Services</h6>

                @can('viewAny', \App\Models\ExpertServiceConsultant::class)
                <li>
                    <a class="{{ request()->routeIs('expert-service-as-consultant.*') ? 'active' : '' }}" href="{{ route('expert-service-as-consultant.index') }}">Expert Service Rendered as Consultant</a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('expert-service-in-conference.*') ? 'active' : '' }}" href="{{ route('expert-service-in-conference.index') }}">Expert Service Rendered in Conference, Workshops, and/or Training Course for Professional</a>
                </li>
                <li>
                    <a class="{{ request()->routeIs('expert-service-in-academic.*') ? 'active' : '' }}" href="{{ route('expert-service-in-academic.index') }}">Expert Service Rendered in Academic Journals/Books/Publication/Newsletter/Creative Works</a>
                </li>
                @endcan
                @can('viewAny', \App\Models\ExtensionService::class)
                <li><a class="{{ request()->routeIs('extension-service.*') ? 'active' : '' }}" href="{{ route('extension-service.index') }}">Extension Program/Project/Activity</a></li>
                @endcan
                @can('viewAny', \App\Models\Partnership::class)
                <li><a class="{{ request()->routeIs('partnership.*') ? 'active' : '' }}" href="{{ route('partnership.index') }}">Partnership/Linkages/Network</a></li>
                @endcan
                @can('viewAny', \App\Models\Mobility::class)
                <li><a class="{{ request()->routeIs('mobility.*') ? 'active' : '' }}" href="{{ route('mobility.index') }}">Inter-country Mobility</a></li>
                @endcan
                {{-- For College and Departments --}}
                @can('viewAny', \App\Models\OutreachProgram::class)
                <li><a class="{{ request()->routeIs('outreach-program.*') ? 'active' : '' }}" href="{{ route('outreach-program.index') }}">Community Relation and Outreach Program</a></li>
                @endcan
            </ul>
            @can('viewAny', \App\Models\Invention::class)
            <ul>
                <h6 class="menu-category">Inventions, Innovation, & Creativity</h6>
                <li><a class="{{ request()->routeIs('invention-innovation-creative.*') ? 'active' : '' }}" href="{{ route('invention-innovation-creative.index') }}">Inventions, Innovation, and Creativity</a></li>
            </ul>
            @endcan
        </div>
        <div class="col-md-4">
            
            @can('viewAny', \App\Models\Research::class)
            <ul>
                <h6 class="menu-category">Research & Book Chapter</h6>
                <li><a class="{{ request()->routeIs('research.*') ? 'active' : '' }}" href="{{ route('research.index') }}">Research Registration</a></li>
            </ul>
            @endcan
            @can('viewAny', \App\Models\Request::class)
            <ul>
                <h6 class="menu-category">Requests & Queries</h6>
                <li><a class="{{ request()->routeIs('request.*') ? 'active' : '' }}" href="{{ route('request.index') }}">Requests and Queries Acted Upon</a></li>
            </ul>
            @endcan
            <ul>
                <h6 class="menu-category">Others</h6>
                <li><a href="">Accomplishments Based on OPCR</a></li>
                <li><a href="">Academic Special Tasks</a></li>
                @can('manage', \App\Models\AdminSpecialTask::class)
                <li><a href="{{ route('admin-special-tasks.index') }}">Admin Special Tasks</a></li>
                @endcan
            </ul>
        </div>
    </div>
</div>