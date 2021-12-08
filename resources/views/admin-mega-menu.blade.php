<div class="menu-sub animate slideIn">
    <div class="row menu-row">
        <div class="col-md-4 menu-category others">
            <h6 style="font-weight: bold;">OTHER ACCOMPLISHMENTS</h6>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <ul>
                <h6 class="menu-category">Professional Study</h6>
                <li><a href="">Professional Study</a></li>
            </ul>
            <ul>
                <h6 class="menu-category">Employee Development</h6>
                <li><a href="">Awards and Recognition</a></li>
                <li><a href="">Officership/Membership</a></li>
                <li><a href="">Seminars</a></li>
                <li><a href="">Trainings</a></li>
            </ul>
            <ul>
                <h6 class="menu-category">University Functions</h6>
                <li><a href="">Attendance in University Functions</a></li>
            </ul>

        </div>

        <div class="col-md-3">
            <ul>
                <h6 class="menu-category">Requests and Queries</h6>
                <li><a href="">Requests and Queries Acted Upon</a></li>
            </ul>
            <ul>
                <h6 class="menu-category">IPCR</h6>
                <li><a href="">Accomplishments based on OPCR</a></li>
                <li><a href="">Special Tasks</a></li>
            </ul>
        </div>

        <div class="col-md-3" style="border-left: 0.01em solid lightgray;">
            <ul>
                <h6 class="menu-category">Research and Book Chapter</h6>
                <li><a href="{{ route('research.index') }}">Research Registration</a></li>
            </ul>
            <ul>
                <h6 class="menu-category">Academic Program Development</h6>
                <li><a href="{{ route('syllabus.index') }}">Course Syllabus</a></li>
                <li><a href="{{ route('rtmmi.index') }}">Reference/Textbook/Module/<br> Monographs/Instructional Materials</a></li>
                {{-- For College and Departments --}}
                <!-- <li><a href="{{ route('viable-project.index') }}">Viable Demonstration Projects</a></li>
                <li><a href="{{ route('college-department-award.index') }}">Awards and Recognition <br>Received by the Office</a></li> -->
            </ul>
            <ul>
                <h6 class="menu-category">Inventions, Innovation, and Creativity</h6>
                <li><a href="{{ route('invention-innovation-creative.index') }}">Inventions, Innovation, and Creativity</a></li>
            </ul>
        </div>
        <div class="col-md-3">

            <ul>
                <h6 class="menu-category">Extension Programs and Expert Services</h6>
                
                <li><a href="{{ route('expert-service-as-consultant.index') }}">Expert Services Rendered</a></li>
                <li><a href="{{ route('extension-service.index') }}">Extension Services</a></li>
                <li><a href="{{ route('partnership.index') }}">Partnership/Linkages/Network</a></li>
                <li><a href="{{ route('mobility.index') }}">Inter-country Mobility</a></li>
                {{-- For College and Departments --}}
                <li><a href="{{ route('outreach-program.index') }}">Community Relation and Outreach Program</a></li>
            </ul>
        </div>
    </div>
</div>