<a href="{{ route('to-finalize.index') }}" class="submission-menu {{ request()->routeIs('to-finalize.index') || request()->routeIs('submissions.getCollege') ? 'active' : ''}} ml-3">To Finalize</a>
@if (in_array(5, $roles))
    <a href="{{ route('chairperson.index') }}" class="submission-menu {{ request()->routeIs('chairperson.index') ? 'active' : ''}}">To Review</a>   
    <a href="{{ route('submissions.accepted.index') }}" class="submission-menu {{ request()->routeIs('submissions.accepted.index') ? 'active' : ''}}">Approved</a>
@endif
@if (in_array(6, $roles))
    <a href="{{ route('dean.index') }}" class="submission-menu {{ request()->routeIs('dean.index') ? 'active' : ''}}">To Review</a>   
    <a href="{{ route('submissions.accepted.index') }}" class="submission-menu {{ request()->routeIs('submissions.accepted.index') ? 'active' : ''}}">Approved</a>
@endif
@if (in_array(7, $roles))
    <a href="{{ route('sector.index') }}" class="submission-menu {{ request()->routeIs('sector.index') ? 'active' : ''}}">To Review</a>  
    <a href="{{ route('submissions.accepted.index') }}" class="submission-menu {{ request()->routeIs('submissions.accepted.index') ? 'active' : ''}}">Approved</a>  
@endif
@if (in_array(8, $roles))
    <a href="{{ route('ipqmso.index') }}" class="submission-menu {{ request()->routeIs('ipqmso.index') ? 'active' : ''}}">To Review</a> 
    <a href="{{ route('submissions.accepted.index') }}" class="submission-menu {{ request()->routeIs('submissions.accepted.index') ? 'active' : ''}}">Approved</a>

@endif
    <a href="{{ route('submissions.denied.index') }}" class="submission-menu {{ request()->routeIs('submissions.denied.index') ? 'active' : ''}}">Denied</a>
@if (in_array(8, $roles))
    <a href="{{ route('reports.all') }}" class="submission-menu {{ request()->routeIs('reports.all') ? 'active' : ''}}">All Accomplishments</a>   
@endif