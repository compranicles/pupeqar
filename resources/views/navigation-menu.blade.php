<nav class="navbar navbar-expand-md navbar-dark border-bottom sticky-top" style="background-color: #212529">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand mr-4" href="{{ route('dashboard') }}" style="color:white">
            <img src="{{ URL('storage/logo2.png') }}" width="36" class="mr-1">
            PUP eQAR
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="main-nav-item">
                    <x-jet-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        {{ __('Home') }}
                    </x-jet-nav-link>
                </li>

                @can('viewAny', App\Models\User::class)
                <li class="nav-item dropdown">
                    <a class="nav-link main-dropdown {{ request()->routeIs('admin.users.*') ? 'active' : ''}} {{ request()->routeIs('admin.roles.*') ? 'active' : ''}} {{ request()->routeIs('admin.permissions.*') ? 'active' : ''}}" role="button" data-bs-toggle="dropdown" aria-expanded="false" :active="request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') || request()->routeIs('admin.permissions.*')">
                        Authentication
                    </a>
                    <ul class="dropdown-menu animate slideIn" aria-labelledby="navbarDropdown">
                        @can('viewAny', App\Models\User::class)
                        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Users</a></li>
                        @endcan

                        @can('viewAny', App\Models\Role::class)
                        <li><a class="dropdown-item" href="{{ route('admin.roles.index') }}">Roles</a></li>
                        @endcan

                        @can('viewAny', App\Models\Authentication\Permission::class)
                        <li><a class="dropdown-item" href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                        @endcan
                    </ul>
                </li>
                @endcan

                @can('viewAny', App\Models\Announcement::class)
                <li class="navbar-nav mr-auto main-nav-item">
                    <x-jet-nav-link href="{{ route('maintenances.index') }}" :active="request()->routeIs('maintenances.*') || request()->routeIs('announcements.*') || request()->routeIs('dropdowns.*') || request()->routeIs('research-forms.*') || request()->routeIs('report-types.*') || request()->routeIs('report-categories.*')">
                        {{ __('Maintenances') }}
                    </x-jet-nav-link>
                </li>
                @endcan

                <li class="nav-item main-nav-item" id="accomplishmentlink">
                    <a id="accomplishment" class="nav-link @if (request()->routeIs('research.*') || request()->routeIs('invention-innovation-creative.*') ||
                        request()->routeIs('technical-extension.*') || request()->routeIs('college-department-award.*') || 
                        request()->routeIs('viable-project.*') || request()->routeIs('student-training.*') || 
                        request()->routeIs('student-award.*') || request()->routeIs('rtmmi.*') || 
                        request()->routeIs('syllabus.*') || request()->routeIs('outreach-program.*') || 
                        request()->routeIs('mobility.*') || request()->routeIs('partnership.*') || 
                        request()->routeIs('extension-service.*')) || request()->routeIs('expert-service-as-consultant.*') ||
                        request()->routeIs('expert-service-in-conference.*') || request()->routeIs('expert-service-in-academic.*')
                        active @endif 
                        " role="button">Accomplishments</a>
                    @include('mega-menu')
                </li>

                <li class="navbar-nav mr-auto main-nav-item">
                    <x-jet-nav-link :active="request()->routeIs('submissions.*')|| request()->routeIs('chairperson.*') || request()->routeIs('dean.*') || request()->routeIs('sector.*') || request()->routeIs('ipqmso.*') || request()->routeIs('extensionist.*') || request()->routeIs('researcher.*')|| request()->routeIs('reports.*')" href="{{ route('to-finalize.index') }}">
                        {{ __('Submissions') }}

                    </x-jet-nav-link>
                </li>
{{-- 
                <li class="nav-item dropdown mr-auto main-nav-item">
                    <a class="nav-link @if (request()->routeIs('faculty.*') || request()->routeIs('chairpersons.*') || request()->routeIs('dean.*') || request()->routeIs('sector.*') || request()->routeIs('ipqmso.*') || request()->routeIs('reports.*')) active font-weight-bold @endif" 
                        id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false" >
                        Reports
                    </a>
                    <ul class="dropdown-menu animate slideIn" aria-labelledby="navbarDropdown">
                       
                        <li><a class="dropdown-item" href="{{ route('faculty.index') }}">Individual</a></li>
                       
                        <li><a class="dropdown-item" href="{{ route('chairperson.index') }}">Department</a></li>

                        <li><a class="dropdown-item" href="{{ route('dean.index') }}">College</a></li>

                        <li><a class="dropdown-item" href="{{ route('sector.index') }}">Sector</a></li>

                        <li><a class="dropdown-item" href="{{ route('ipqmso.index') }}">IPQMSO</a></li>

                        <li><a class="dropdown-item" href="{{ route('reports.all') }}">All</a></li>
                    </ul>
                </li> --}}


            </ul>
            
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto align-items-baseline">

                <!-- Settings Dropdown -->
                @auth
                    <x-jet-dropdown id="settingsDropdown">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <img class="rounded-circle" width="32" height="32" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                            @else
                                {{ Auth::user()->first_name }}

                                <svg class="ml-2" width="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <h6 class="dropdown-header small text-muted">
                                {{ __('Manage Account') }}
                            </h6>

                            <x-jet-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-jet-dropdown-link>
                            <hr class="dropdown-divider">

                            <!-- Authentication -->
                            <x-jet-dropdown-link href="{{ route('logout') }}"
                                                 onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                {{ __('Log out') }}
                            </x-jet-dropdown-link>
                            <form method="POST" id="logout-form" action="{{ route('logout') }}">
                                @csrf
                            </form>
                        </x-slot>
                    </x-jet-dropdown>
                @endauth
            </ul>
        </div>
    </div>

    @push('scripts')
        <script>
            let dropdownBtn = document.querySelector('#accomplishmentlink');
            let menuContent = document.querySelector('.menu-sub');
            let menuLink = document.querySelectorAll('.menu-sub a');
            dropdownBtn.addEventListener('click',()=>{
                if (menuContent.style.display===""){
                    menuContent.style.display="block";
                    menuContent.style.cssFloat="none";
                } else {
                    menuContent.style.display="";
                }
            });
            menuContent.addEventListener('click', ()=>{
                if (menuContent.style.display===""){
                    menuContent.style.display="block";
                } else {
                    menuContent.style.display="";
                }
            });

            for(var i=0; i < menuLink.length; i++) {
                menuLink[i].addEventListener('click', ()=>{
                    menuContent.style.display="";
                });
            }

            $(document).click((event) => {
                if (!$(event.target).closest('#accomplishmentlink').length) {
                    // the click occured outside
                    if (!$(event.target).closest('.menu-sub').length) {
                        menuContent.style.display="";
                    }      
                }  
            });
        </script>
    @endpush
</nav>