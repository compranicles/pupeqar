<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n3">
                    @can('viewAny', App\Models\Announcement::class)
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('announcements.index') }}" class="text-dark"  class="text-dark" :active="request()->routeIs('announcements.*')">
                            {{ __('Announcements') }}
                        </x-jet-nav-link>
                    </li>
                    @endcan

                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('colleges.index') }}" class="text-dark"  :active="request()->routeIs('colleges.*')">
                            {{ __('Colleges') }}
                        </x-jet-nav-link>
                    </li>
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('departments.index') }}" class="text-dark"  :active="request()->routeIs('departments.*')">
                            {{ __('Departments') }}
                        </x-jet-nav-link>
                    </li>

                    @can('viewAny', App\Models\Maintenance\Currency::class)
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('currencies.index') }}" class="text-dark"  :active="request()->routeIs('currencies.*')">
                            {{ __('Currencies') }}
                        </x-jet-nav-link>
                    </li>
                    @endcan
                    
                    @can('viewAny', App\Models\FormBuilder\Dropdown::class)
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('dropdowns.index') }}" class="text-dark" :active="request()->routeIs('dropdowns.*')">
                            {{ __('Dropdowns') }}
                        </x-jet-nav-link>
                    </li>
                    @endcan
                    
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('report-types.index') }}" class="text-dark" :active="request()->routeIs('report-types.*') || request()->routeIs('report-categories.*')">
                            {{ __('Reports') }}
                        </x-jet-nav-link>
                    </li>

                    @can('viewAny', App\Models\FormBuilder\ResearchForm::class)
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('research-forms.index') }}" class="text-dark" :active="request()->routeIs('research-forms.*')">
                            {{ __('Research') }}
                        </x-jet-nav-link>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\FormBuilder\ResearchForm::class)
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('invention-forms.index') }}" class="text-dark" :active="request()->routeIs('invention-forms.*')">
                            {{ __('Invention') }}
                        </x-jet-nav-link>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>
</div>