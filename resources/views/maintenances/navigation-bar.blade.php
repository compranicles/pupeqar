<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n2">
                    @can('viewAny', App\Models\Announcement::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('announcements.*') ? 'active' : '' }}" href="{{ route('announcements.index') }}">Announcements</a>
                    </li>
                    @endcan

                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('maintenance.quarter.*') ? 'active' : '' }}" href="{{ route('maintenance.quarter.index') }}">Quarter & Year</a>
                    </li>

                    @can('viewAny', App\Models\Maintenance\Sector::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('sectors.*') ? 'active' : '' }}" href="{{ route('sectors.maintenance.index') }}">Sectors</a>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\Maintenance\College::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('colleges.*') ? 'active' : '' }}" href="{{ route('colleges.index') }}">College/Branch/Campus/Office</a>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\Maintenance\Sector::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('departments.*') ? 'active' : '' }}" href="{{ route('departments.index') }}">Departments</a>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\Maintenance\Currency::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('currencies.*') ? 'active' : '' }}" href="{{ route('currencies.index') }}">Currencies</a>
                    </li>
                    @endcan
                    
                    @can('viewAny', App\Models\FormBuilder\Dropdown::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('dropdowns.*') ? 'active' : '' }}" href="{{ route('dropdowns.index') }}">Dropdowns</a>
                    </li>
                    @endcan
                    
                    @can('viewAny', App\Models\Maintenance\ReportType::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('report-types.*') || request()->routeIs('report-categories.*') ? 'active' : '' }}" href="{{ route('report-types.index') }}">Submissions</a>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\Maintenance\GenerateType::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('maintenance.generate.*') ? 'active' : '' }}" href="{{ route('maintenance.generate.type') }}">Reports</a>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\FormBuilder\ResearchForm::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('research-forms.*') ? 'active' : '' }}" href="{{ route('research-forms.index') }}">Research</a>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\FormBuilder\ResearchForm::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('invention-forms.*') ? 'active' : '' }}" href="{{ route('invention-forms.index') }}">Invention</a>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\FormBuilder\ExtensionProgramForm::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('extension-program-forms.*') ? 'active' : '' }}" href="{{ route('extension-program-forms.index') }}">Extension Programs</a>
                    </li>
                    @endcan

                    @can('manage', App\Models\FormBuilder\AcademicDevelopmentForm::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('academic-module-forms.*') ? 'active' : '' }}" href="{{ route('academic-module-forms.index') }}">Academic Module</a>
                    </li>
                    @endcan

                    @can('manage', App\Models\FormBuilder\IPCRForm::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('ipcr-forms.*') ? 'active' : '' }}" href="{{ route('ipcr-forms.index') }}">IPCR</a>
                    </li>
                    @endcan

                    @can('manage', App\Models\Maintenance\HRISForm::class)
                    <li class="nav-sub-menu ml-4 mr-4">
                        <a class="text-dark {{ request()->routeIs('hris-forms.*') ? 'active' : '' }}" href="{{ route('hris-forms.index') }}">HRIS</a>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>
</div>