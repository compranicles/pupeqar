<div class="menu-sub animate slideIn">
    <div class="menu-col-1">
        <ul>
        <h6 class="menu-category">Faculty Accomplishment</h6>
            <li><a href="">Professional Study</a></li>
        </ul>
        
        <ul>
            <h6 class="menu-category">Employee Development</h6>
            <li><a href="">Awards and Recognition</a></li>
            <li><a href="">Officership/Membership</a></li>
            <li><a href="">Seminars</a></li>
            <li><a href="">Trainings</a></li>
        </ul>
        @can('viewAny', App\Models\Research::class)
        <ul>
            <h6 class="menu-category">Research and Book Chapter</h6>
            <li><a href="{{ route('research.index') }}">Research Registration</a></li>
        </ul>
        @endcan
    </div>
    <div class="menu-col-2">
        
        <ul>
            <h6 class="menu-category">Inventions, Innovation, and Creativity</h6>
            <li><a href="{{ route('inventions.index') }}">Inventions, Innovation, and Creativity</a></li>
        </ul>
        <ul>
            <h6 class="menu-category">Extension Programs and Expert Services</h6>
            <li><a href="">Expert Services Rendered</a></li>
            <li><a href="">Extension Services</a></li>
            <li><a href="">Partnership/Linkages/Network</a></li>
            <li><a href="">Inter-country Mobility</a></li>
        </ul>
    </div>
    <div class="menu-col-3">
        <ul>
            <h6 class="menu-category">Academic Program Development</h6>
            <li><a href="">Course Syllabus</a></li>
            <li><a href="">Reference/Textbook/Module/<br>Monographs/
            Instructional Materials</a></li>
        </ul>
        <ul>
            <h6 class="menu-category">Others</h6>
            <li><a href="">Attendance in University Functions</a></li>
            <li><a href="">Special Tasks</a></li>
        </ul>
    </div>
</div>