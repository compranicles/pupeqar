<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n3">
                    <li class="submenu-nav-item">
                        <a class="text-dark extension-program-sub-menu {{ request()->routeIs('expert-service-as-consultant.*') ? 'active' : '' }}" href="{{ route('expert-service-as-consultant.index') }}">Expert Service Rendered as Consultant</a>
                    </li>

                    <li class="submenu-nav-item">
                        <a class="text-dark extension-program-sub-menu {{ request()->routeIs('expert-service-in-conference.*') ? 'active' : '' }}" href="{{ route('expert-service-in-conference.index') }}">Expert Service Rendered in Conference, Workshops, and/or Training Course for Professional</a>
                    </li>

                    <li class="submenu-nav-item">
                        <a class="text-dark extension-program-sub-menu {{ request()->routeIs('expert-service-in-academic.*') ? 'active' : '' }}" href="{{ route('expert-service-in-academic.index') }}">Expert Service Rendered in Academic Journals/Books/Publication/Newsletter/Creative Works</a>
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>