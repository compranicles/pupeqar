<a href="{{ route('profile.personal') }}" class="submission-menu ml-3 {{ request()->routeIs('profile.personal') ? 'active' : '' }}">Personal Profile</a>
<a href="{{ route('profile.educationalBackground') }}" class="submission-menu {{ request()->routeIs('profile.educationalBackground') ? 'active' : '' }}">Educational Background</a>
<a href="{{ route('profile.educationalDegree') }}" class="submission-menu {{ request()->routeIs('profile.educationalDegree') ? 'active' : '' }}">Educational Degree</a>
<a href="{{ route('profile.eligibility') }}" class="submission-menu {{ request()->routeIs('profile.eligibility') ? 'active' : '' }}">Eligibility</a>
<a href="{{ route('profile.workExperience') }}" class="submission-menu {{ request()->routeIs('profile.workExperience') ? 'active' : '' }}">Work Experience</a>
<a href="{{ route('profile.voluntaryWork') }}" class="submission-menu {{ request()->routeIs('profile.voluntaryWork') ? 'active' : '' }}">Voluntary Works</a>
