<a href="{{ route('to-finalize.index') }}" class="submission-menu {{ request()->routeIs('to-finalize.index') || request()->routeIs('submissions.getCollege') ? 'active' : ''}} ml-3">To Finalize</a>

{{-- Departments' --}}
@if (in_array(5, $roles))
    <a href="{{ route('chairperson.index') }}" class="submission-menu {{ request()->routeIs('chairperson.index') ? 'active' : ''}}">To Receive - Department/s</a>   
    @forelse ( $departments as $row)
        <a href="{{ route('submissions.departmentaccomp.index', $row->department_id) }}" class="submission-menu {{ request()->routeIs('submissions.departmentaccomp.index', $row->id) ? 'active' : ''}}">
            {{ $row->code }} - Accomplishments
        </a>   
    @empty

    @endforelse 
@endif

{{-- Colleges/Branches/Offices --}}
@if (in_array(6, $roles))
    <a href="{{ route('dean.index') }}" class="submission-menu {{ request()->routeIs('dean.index') ? 'active' : ''}}">To Receive - College/Branch/Campus/Office/s</a>   
    @forelse ( $colleges as $row)
        <a href="{{ route('submissions.collegeaccomp.index', $row->college_id) }}" class="submission-menu {{ request()->routeIs('submissions.collegeaccomp.index', $row->id) ? 'active' : ''}}">
            {{ $row->code }} - Accomplishments
        </a>   
    @empty
    @endforelse
@endif

{{-- Sectors/VPs --}}
@if (in_array(7, $roles))
    <a href="{{ route('sector.index') }}" class="submission-menu {{ request()->routeIs('sector.index') ? 'active' : ''}}">To Receive - Sector</a>
    @forelse ( $sectors as $row)
        <a href="{{ route('submissions.sectoraccomp.index', $row->sector_id) }}" class="submission-menu {{ request()->routeIs('submissions.sectoraccomp.index') ? 'active' : ''}}">
            {{ $row->code }} - Accomplishments
        </a>  
    @empty
    @endforelse
@endif

{{-- IPQMSOs --}}
@if (in_array(8, $roles))
    <a href="{{ route('ipqmso.index') }}" class="submission-menu {{ request()->routeIs('ipqmso.index') ? 'active' : ''}}">To Receive - IPQMSO</a> 
    <a href="{{ route('submissions.ipqmsoaccomp.index') }}" class="submission-menu {{ request()->routeIs('submissions.ipqmsoaccomp.index') ? 'active' : ''}}">
        All Accomplishments
    </a>  
@endif

{{-- Researchers --}}
@if (in_array(10, $roles))
    <a href="{{ route('researcher.index') }}" class="submission-menu {{ request()->routeIs('researcher.index') ? 'active' : ''}}">To Receive - Researcher</a> 
    @forelse ( $departmentsResearch as $row)
        <a href="{{ route('submissions.researchaccomp.index', $row->department_id) }}" class="submission-menu {{ request()->routeIs('submissions.researchaccomp.index') ? 'active' : ''}}">
            {{ $row->code }} - Accomplishments
        </a>  
    @empty
    @endforelse
@endif

{{-- Extensionist --}}
@if (in_array(11, $roles))
    <a href="{{ route('extensionist.index') }}" class="submission-menu {{ request()->routeIs('extensionist.index') ? 'active' : ''}}">To Receive - Extensionist</a> 
    @forelse ( $departmentsExtension as $row)
        <a href="{{ route('submissions.extensionaccomp.index', $row->department_id) }}" class="submission-menu {{ request()->routeIs('submissions.extensionaccomp.index') ? 'active' : ''}}">
            {{ $row->code }} - Accomplishments
        </a>  
    @empty
    @endforelse
@endif


{{-- My Accomplishments --}}
<a href="{{ route('submissions.myaccomp.index') }}" class="submission-menu {{ request()->routeIs('submissions.myaccomp.index') ? 'active' : ''}}">My Accomplishments</a>