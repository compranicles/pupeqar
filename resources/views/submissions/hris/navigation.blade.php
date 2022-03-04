<a href="{{ route('to-finalize.index') }}" class="submission-menu {{ request()->routeIs('to-finalize.index') || request()->routeIs('submissions.getCollege') ? 'active' : ''}} ml-3">eQAR</a>

<a href="{{ route('submissions.educ.index') }}" class="submission-menu {{ request()->routeIs('submissions.educ.*') ? 'active' : '' }} ml-3">Education</a>

<a href="{{ route('submissions.development.index') }}" class="submission-menu {{ request()->routeIs('submissions.development.*') ? 'active' : '' }} ml-3">Trainings</a>

<a href="{{ route('submissions.officership.index') }}" class="submission-menu {{ request()->routeIs('submissions.officership.*') ? 'active' : '' }} ml-3">Memberships</a>
