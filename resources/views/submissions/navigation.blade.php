<a href="{{ route('to-finalize.index') }}" class="submission-menu {{ request()->routeIs('to-finalize.index') || request()->routeIs('submissions.getCollege') ? 'active' : ''}} ml-3">To Finalize</a>

{{-- Departments' --}}
@if (in_array(5, $roles))
    <a href="{{ route('chairperson.index') }}" class="submission-menu {{ request()->routeIs('chairperson.index') ? 'active' : ''}}">To Receive - Department/s</a>   
    @forelse ( $departments as $row)
        <a href="{{ route('submissions.departmentaccomp.index', $row->department_id) }}" class="submission-menu {{ request()->routeIs('submissions.departmentaccomp.index', $row->id) ? 'active' : ''}}">
            {{ $row->name }} - Accomplishments
        </a>   
    @empty

    @endforelse 
@endif

{{-- Colleges/Branches/Offices --}}
@if (in_array(6, $roles))
    <a href="{{ route('dean.index') }}" class="submission-menu {{ request()->routeIs('dean.index') ? 'active' : ''}}">To Receive - College/Branch/Campus/Office/s</a>   
    @forelse ( $colleges as $row)
        <a href="{{ route('submissions.collegeaccomp.index', $row->college_id) }}" class="submission-menu {{ request()->routeIs('submissions.collegeaccomp.index', $row->id) ? 'active' : ''}}">
            {{ $row->name }} - Accomplishments
        </a>   
    @empty
    @endforelse
@endif

{{-- Sectors/VPs --}}
@if (in_array(7, $roles))
    <a href="{{ route('sector.index') }}" class="submission-menu {{ request()->routeIs('sector.index') ? 'active' : ''}}">To Receive - Sector</a>  
@endif

{{-- IPQMSOs --}}
@if (in_array(8, $roles))
    <a href="{{ route('ipqmso.index') }}" class="submission-menu {{ request()->routeIs('ipqmso.index') ? 'active' : ''}}">To Receive - All</a> 
    <a href="{{ route('reports.all') }}" class="submission-menu {{ request()->routeIs('reports.all') ? 'active' : ''}}">All Accomplishments</a>   
@endif


{{-- My Accomplishments --}}
<a href="{{ route('submissions.myaccomp.index') }}" class="submission-menu {{ request()->routeIs('submissions.myaccomp.index') ? 'active' : ''}}">My Accomplishments</a>