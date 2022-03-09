<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n3">
                    <li class="submenu-nav-item">
                        <a href="{{ route('submissions.educ.index') }}" class="text-dark extension-program-sub-menu {{ request()->routeIs('submissions.educ.*') ? 'active' : '' }} ml-3">Education</a>
                    </li>

                    <li class="submenu-nav-item">
                        <a href="{{ route('submissions.development.index') }}" class="text-dark extension-program-sub-menu {{ request()->routeIs('submissions.development.*') ? 'active' : '' }} ml-3">Trainings</a>
                    </li>

                    <li class="submenu-nav-item">
                        <a href="{{ route('submissions.officership.index') }}" class="text-dark extension-program-sub-menu {{ request()->routeIs('submissions.officership.*') ? 'active' : '' }} ml-3">Memberships</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>