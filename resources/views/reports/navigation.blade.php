{{-- My Accomplishments --}}
<a href="{{ route('reports.consolidate.myaccomplishments') }}" class="submission-menu {{ request()->routeIs('reports.consolidate.myaccomplishments') || request()->routeIs('reports.consolidate.myaccomplishments.*') ? 'active' : '' }} ">My Accomplishments</a>

{{-- Departments' --}}
@if (in_array(5, $roles))
    <a href="{{ route('chairperson.index') }}" class="submission-menu {{ request()->routeIs('chairperson.index') ? 'active' : ''}}">To Receive - Department</a>   
    @forelse ( $departments as $row)
        <a href="{{ route('reports.consolidate.department', $row->department_id) }}" class="submission-menu {{ request()->routeIs('reports.consolidate.department') || request()->routeIs('reports.consolidate.department.*') ? 'active' : ''}}">  
            {{ $row->code }} - Accomplishments
        </a>   
    @empty

    @endforelse 
@endif

{{-- Colleges/Branches/Offices --}}
@if (in_array(6, $roles))
    <a href="{{ route('director.index') }}" class="submission-menu {{ request()->routeIs('director.index') ? 'active' : ''}}">To Receive - College/Branch/Campus/Office/s</a>   
    @forelse ( $colleges as $row)
        <a href="{{ route('reports.consolidate.college', $row->college_id) }}" class="submission-menu {{ request()->routeIs('reports.consolidate.college') || request()->routeIs('reports.consolidate.college.*') ? 'active' : ''}}">
            {{ $row->code }} - Accomplishments
        </a>   
    @empty
    @endforelse
@endif

{{-- Sectors/VPs --}}
@if (in_array(7, $roles))
    <a href="{{ route('sector.index') }}" class="submission-menu {{ request()->routeIs('sector.index') ? 'active' : ''}}">To Receive - Sector</a>
    @forelse ( $sectors as $row)
        <a href="{{ route('reports.consolidate.sector', $row->sector_id) }}" class="submission-menu {{ request()->routeIs('reports.consolidate.sector') ? 'active' : ''}}">
            {{ $row->code }} - Accomplishments
        </a>  
    @empty
    @endforelse
@endif

{{-- IPQMSOs --}}
@if (in_array(8, $roles))
    <a href="{{ route('ipqmso.index') }}" class="submission-menu {{ request()->routeIs('ipqmso.index') ? 'active' : ''}}">To Receive - IPQMSO</a> 
    <a href="{{ route('reports.consolidate.ipqmso') }}" class="submission-menu {{ request()->routeIs('reports.consolidate.ipqmso') || request()->routeIs('reports.consolidate.ipqmso.*') ? 'active' : ''}}">
        All Accomplishments
    </a>  
@endif

{{-- Researchers --}}
@if (in_array(10, $roles))
    <a href="{{ route('researcher.index') }}" class="submission-menu {{ request()->routeIs('researcher.index') ? 'active' : ''}}">To Receive - Researcher</a> 
    @forelse ( $departmentsResearch as $row)
        <a href="{{ route('reports.consolidate.research', $row->department_id) }}" class="submission-menu {{ request()->routeIs('reports.consolidate.research') ? 'active' : ''}}">
            {{ $row->code }} - Accomplishments
        </a>  
    @empty
    @endforelse
@endif

{{-- Extensionist --}}
@if (in_array(11, $roles))
    <a href="{{ route('extensionist.index') }}" class="submission-menu {{ request()->routeIs('extensionist.index') ? 'active' : ''}}">To Receive - Extensionist</a> 
    @forelse ( $departmentsExtension as $row)
        <a href="{{ route('reports.consolidate.extension', $row->department_id) }}" class="submission-menu {{ request()->routeIs('reports.consolidate.extension') ? 'active' : ''}}">
            {{ $row->code }} - Accomplishments
        </a>  
    @empty
    @endforelse
@endif