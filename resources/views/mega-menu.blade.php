<div class="menu-sub animate slideIn">
    <div class="menu-col-1">
        <ul>
            <h6 class="menu-category">Employee Development</h6>
            <li><a href="">Awards and Recognition</a></li>
            <li><a href="">Officership/Membership</a></li>
            <li><a href="">Seminars</a></li>
            <li><a href="">Trainings</a></li>
        </ul>
        <ul>
            <h6 class="menu-category">Extension Programs and Expert Services</h6>
            
            <li><a href="{{ route('faculty.expert-service-as-consultant.index') }}">Expert Services Rendered</a></li>
            @can('viewAny', App\Models\ExtensionService::class)
            <li><a href="{{ route('faculty.extension-service.index') }}">Extension Services</a></li>
            @endcan
            @can('viewAny', App\Models\Partnership::class)
            <li><a href="{{ route('partnership.index') }}">Partnership/Linkages/Network</a></li>
            @endcan
            @can('viewAny', App\Models\Mobility::class)
            <li><a href="{{ route('mobility.index') }}">Inter-country Mobility</a></li>
            @endcan
            {{-- For College and Departments --}}
            @can('viewAny', App\Models\OutreachProgram::class)
            <li><a href="{{ route('outreach-program.index') }}">Community Relation and Outreach Program</a></li>
            @endcan
        </ul>
    </div>
    <div class="menu-col-2">
        <ul>
            <h6 class="menu-category">Academic Program Development</h6>
            @can('viewAny', App\Models\Syllabus::class)
            <li><a href="{{ route('faculty.syllabus.index') }}">Course Syllabus</a></li>
            @endcan
            @can('viewAny', App\Models\Reference::class)
            <li><a href="{{ route('faculty.rtmmi.index') }}">Reference/Textbook/Module/<br> Monographs/Instructional Materials</a></li>
            @endcan
            {{-- For College and Departments --}}
            @can('viewAny', App\Models\StudentAward::class)
            <li><a href="{{ route('student-award.index') }}">Student Awards and Recognition</a></li>
            @endcan
            @can('viewAny', App\Models\StudentTraining::class)
            <li><a href="{{ route('student-training.index') }}">Student Attended Seminars and Trainings</a></li>
            @endcan
            @can('viewAny', App\Models\ViableProject::class)
            <li><a href="{{ route('viable-project.index') }}">Viable Demonstration Projects</a></li>
            @endcan
            @can('viewAny', App\Models\CollegeDepartmentAward::class)
            <li><a href="{{ route('college-department-award.index') }}">Awards and Recognition Received<br> by the College and Department</a></li>
            @endcan
            @can('viewAny', App\Models\TechnicalExtension::class)
            <li><a href="{{ route('technical-extension.index') }}">Technical Extension Programs/ Projects/ Activities</a></li>
            @endcan
        </ul>
    </div>
    <div class="menu-col-3">
        <ul>
            <h6 class="menu-category">Faculty Accomplishment</h6>
            <li><a href="">Professional Study</a></li>
        </ul>
        @can('viewAny', App\Models\Invention::class)
        <ul>
            <h6 class="menu-category">Inventions, Innovation, and Creativity</h6>
            <li><a href="{{ route('faculty.invention-innovation-creative.index') }}">Inventions, Innovation, and Creativity</a></li>
        </ul>
        @endcan
        @can('viewAny', App\Models\Research::class)
        <ul>
            <h6 class="menu-category">Research and Book Chapter</h6>
            <li><a href="{{ route('research.index') }}">Research Registration</a></li>
        </ul>
        @endcan
        <ul>
            <h6 class="menu-category">Others</h6>
            <li><a href="">Attendance in University Functions</a></li>
            <li><a href="">Special Tasks</a></li>
        </ul>
    </div>
</div>