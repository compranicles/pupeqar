<a href="{{ route('profile.personal') }}" class="submission-menu ml-3 {{ request()->routeIs('profile.personal') ? 'active' : '' }}">Personal Profile</a>
<a href="{{ route('profile.employment') }}" class="submission-menu {{ request()->routeIs('profile.employment') ? 'active' : '' }}">Employment Profile</a>
<a href="{{ route('profile.education') }}" class="submission-menu {{ request()->routeIs('profile.education') ? 'active' : '' }}">Educational Degree</a>
<a href="{{ route('profile.professionalStudy') }}" class="submission-menu {{ request()->routeIs('profile.professionalStudy') ? 'active' : '' }}">Ongoing/Advanced Professional Study</a>
<a href="{{ route('profile.teaching') }}" class="submission-menu {{ request()->routeIs('profile.teaching') ? 'active' : '' }}">Teaching Discipline</a>