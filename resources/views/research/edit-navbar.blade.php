<div class="card mb-3 research-tabs">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n3">
                    @canany(['viewAny','create', 'update', 'delete'], App\Models\Research::class)
                    <li class="nav-sub-menu">
                        <a href="{{ route('research.edit', $research_code) }}" class="text-dark {{ request()->routeIs('research.edit') ? 'active' : '' }}">
                            {{ __('Registration') }}
                        </a >
                    </li>
                    @endcanany
                    @switch($research_status)
                        @case('28') @case('29') @case('30') @case('31')
                            @canany(['viewAny','create', 'update'], App\Models\ResearchComplete::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.complete', $research_code) }}" class="text-dark {{ request()->routeIs('research.completed.*') ? 'active' : '' }}">
                                    {{ __('Completion') }}
                                </a >
                            </li>
                            @endcanany
                            @break
                        @default
                    @endswitch
                        
                    @if ($noRequisiteRecords[1] != null)
                    @canany(['viewAny','create', 'update'], App\Models\ResearchPresentation::class)
                    <li class="nav-sub-menu">
                        <a href="{{ route('research.presentation', $research_code) }}" class="text-dark {{ request()->routeIs('research.presentation.*') ? 'active' : '' }}">
                            {{ __('Presentation') }}
                        </a >
                    </li>
                    @endcanany
                    @endif

                    @if ($noRequisiteRecords[2] != null)
                    @canany(['viewAny','create', 'update'], App\Models\ResearchPublication::class)
                    <li class="nav-sub-menu">
                        <a href="{{ route('research.publication', $research_code) }}" class="text-dark {{ request()->routeIs('research.publication.*') ? 'active' : '' }}">
                            {{ __('Publication') }}
                        </a >
                    </li>
                    @endcanany
                    @endif

                    @if ($noRequisiteRecords[3] != null)
                    @canany(['viewAny','create', 'update'], App\Models\ResearchCopyright::class)
                    <li class="nav-sub-menu">
                        <a href="{{ route('research.copyright', $research_code) }}" class="text-dark {{ request()->routeIs('research.copyrighted.*') ? 'active' : '' }}">
                            {{ __('Copyright') }}
                        </a >
                    </li>
                    @endcanany
                    @endif
                    
                    @canany(['viewAny','create', 'update'], App\Models\ResearchCitation::class)
                    <li class="nav-sub-menu">
                        <a href="{{ route('research.citation.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.citation.*') ? 'active' : '' }}">
                            {{ __('Citations') }}
                        </a >
                    </li>
                    @endcanany

                    @canany(['viewAny','create', 'update', 'delete'], App\Models\ResearchUtilization::class)
                    <li class="nav-sub-menu">
                        <a href="{{ route('research.utilization.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.utilization.*') ? 'active' : '' }}">
                            {{ __('Utilizations') }}
                        </a >
                    </li>
                    @endcanany
                </ul>
            </div>
        </div>
    </div>
</div>
