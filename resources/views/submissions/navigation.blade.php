<a href="{{ route('to-finalize.index') }}" class="submission-menu {{ request()->routeIs('to-finalize.index') ? 'active' : ''}} ml-3">To Finalize</a>
@if (in_array(5, $roles))
    <a href="{{ route('chairperson.index') }}" class="submission-menu {{ request()->routeIs('chairperson.index') ? 'active' : ''}}">To Review</a>    
@endif
@if (in_array(6, $roles))
    <a href="{{ route('dean.index') }}" class="submission-menu {{ request()->routeIs('dean.index') ? 'active' : ''}}">To Review</a>   
@endif
@if (in_array(7, $roles))
<a href="{{ route('sector.index') }}" class="submission-menu {{ request()->routeIs('sector.index') ? 'active' : ''}}">To Review</a>   
@endif
<a href="{{ route('submissions.denied.index') }}" class="submission-menu {{ request()->routeIs('submissions.denied.index') ? 'active' : ''}}">Denied</a>