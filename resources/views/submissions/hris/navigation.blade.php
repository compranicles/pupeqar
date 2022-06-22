
<a href="{{ route('submissions.educ.index') }}" class="submission-menu {{ request()->routeIs('submissions.educ.*') ? 'active' : '' }} ml-3">Ongoing Studies</a>

<a href="{{ route('submissions.award.index') }}" class="submission-menu {{ request()->routeIs('submissions.award.*') ? 'active' : '' }} ml-3">Outstanding Awards</a>

<a href="{{ route('submissions.development.index') }}" class="submission-menu {{ request()->routeIs('submissions.development.*') ? 'active' : '' }} ml-3">Seminars and Trainings</a>

<a href="{{ route('submissions.officership.index') }}" class="submission-menu {{ request()->routeIs('submissions.officership.*') ? 'active' : '' }} ml-3">Officerships/Memberships</a>
