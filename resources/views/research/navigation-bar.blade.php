<h3 class="font-weight-bold mb-2">Research/Book Chapter - {{ $research[0]->title }}</h3>
<p>
    <a class="back_link" href="{{ route('research.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Research</a>
</p>
<div class="card mb-3 research-tabs">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n3">
                    @switch($research_status)
                        @case('26') 
                            @canany(['viewAny','create', 'update', 'delete'], App\Models\Research::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.show', $research_code) }}" class="text-dark {{ request()->routeIs('research.show') ? 'active' : '' }}">
                                    {{ __('Registration') }}
                                </a >
                            </li>
                            @endcanany
                            @break
                        @case('27')
                            @canany(['viewAny','create', 'update', 'delete'], App\Models\Research::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.show', $research_code) }}" class="text-dark {{ request()->routeIs('research.show') ? 'active' : '' }}">
                                    {{ __('Registration') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update', 'delete'], App\Models\ResearchUtilization::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.utilization.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.utilization.*') ? 'active' : '' }}" >
                                    {{ __('Utilization') }}
                                </a >
                            </li>
                            @endcanany

                            @break
                        @case('28')
                            @canany(['viewAny','create', 'update', 'delete'], App\Models\Research::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.show', $research_code) }}" class="text-dark {{ request()->routeIs('research.show') ? 'active' : '' }}">
                                    {{ __('Registration') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update'], App\Models\ResearchComplete::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.completed.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.completed.*') ? 'active' : '' }}">
                                    {{ __('Completion') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update'], App\Models\ResearchCopyright::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.copyrighted.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.copyrighted.*') ? 'active' : '' }}">
                                    {{ __('Copyright') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update', 'delete'], App\Models\ResearchUtilization::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.utilization.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.utilization.*') ? 'active' : '' }}">
                                    {{ __('Utilization') }}
                                </a >
                            </li>
                            @endcanany
                            
                            @break
                        @case('29')
                            @canany(['viewAny','create', 'update', 'delete'], App\Models\Research::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.show', $research_code) }}" class="text-dark {{ request()->routeIs('research.show') ? 'active' : '' }}">
                                    {{ __('Registration') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update'], App\Models\ResearchComplete::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.completed.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.completed.*') ? 'active' : '' }}">
                                    {{ __('Completion') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update'], App\Models\ResearchPresentation::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.presentation.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.presentation.*') ? 'active' : '' }}">
                                    {{ __('Presentation') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update', 'delete'], App\Models\ResearchCopyright::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.copyrighted.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.copyrighted.*') ? 'active' : '' }}">
                                    {{ __('Copyright') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update', 'delete'], App\Models\ResearchUtilization::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.utilization.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.utilization.*') ? 'active' : '' }}">
                                    {{ __('Utilization') }}
                                </a >
                            </li>
                            @endcanany
                            @break
                        @case('30')
                            @canany(['viewAny','create', 'update', 'delete'], App\Models\Research::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.show', $research_code) }}" class="text-dark {{ request()->routeIs('research.show') ? 'active' : '' }}">
                                    {{ __('Registration') }}
                                </a >
                            </li>
                            @endcanany
        
                            @canany(['viewAny','create', 'update'], App\Models\ResearchComplete::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.completed.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.completed.*') ? 'active' : '' }}">
                                    {{ __('Completion') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update'], App\Models\ResearchCopyright::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.copyrighted.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.copyrighted.*') ? 'active' : '' }}">
                                    {{ __('Copyright') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update'], App\Models\ResearchPublication::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.publication.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.publication.*') ? 'active' : '' }}">
                                    {{ __('Publication') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update', 'delete'], App\Models\ResearchCitation::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.citation.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.ciation.*') ? 'active' : '' }}">
                                    {{ __('Citation') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update', 'delete'], App\Models\ResearchUtilization::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.utilization.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.utilization.*') ? 'active' : '' }}">
                                    {{ __('Utilization') }}
                                </a >
                            </li>
                            @endcanany

                            @break
                        @case('31')
                            @canany(['viewAny','create', 'update', 'delete'], App\Models\Research::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.show', $research_code) }}" class="text-dark {{ request()->routeIs('research.show') ? 'active' : '' }}">
                                    {{ __('Registration') }}
                                </a >
                            </li>
                            @endcanany
        
                            @canany(['viewAny','create', 'update'], App\Models\ResearchComplete::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.completed.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.completed.*') ? 'active' : '' }}">
                                    {{ __('Completion') }}
                                </a >
                            </li>
                            @endcanany
                            
                            @canany(['viewAny','create', 'update'], App\Models\ResearchPublication::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.publication.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.publication.*') ? 'active' : '' }}">
                                    {{ __('Publication') }}
                                </a >
                            </li>
                            @endcanany
        
                            @canany(['viewAny','create', 'update'], App\Models\ResearchPresentation::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.presentation.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.presentation.*') ? 'active' : '' }}">
                                    {{ __('Presentation') }}
                                </a >
                            </li>
                            @endcanany
                            
                            @canany(['viewAny','create', 'update'], App\Models\ResearchCopyright::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.copyrighted.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.copyrighted.*') ? 'active' : '' }}">
                                    {{ __('Copyrighted') }}
                                </a >
                            </li>
                            @endcanany

                            @canany(['viewAny','create', 'update'], App\Models\ResearchCitation::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.citation.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.citation.*') ? 'active' : '' }}">
                                    {{ __('Citation') }}
                                </a >
                            </li>
                            @endcanany
        
                            @canany(['viewAny','create', 'update', 'delete'], App\Models\ResearchUtilization::class)
                            <li class="nav-sub-menu">
                                <a href="{{ route('research.utilization.index', $research_code) }}" class="text-dark {{ request()->routeIs('research.utilization.*') ? 'active' : '' }}">
                                    {{ __('Utilization') }}
                                </a >
                            </li>
                            @endcanany

                            @break
                        @case('32')
                            @canany(['viewAny','create', 'update', 'delete'], App\Models\Research::class)
                            <li class="nav-sub-menu">
                                <a  href="{{ route('research.show', $research_code) }}" class="text-dark {{ request()->routeIs('research.show') ? 'active' : '' }}">
                                    {{ __('Registration') }}
                                </a >
                            </li>
                            @endcanany
                            @break
                    
                        @default
                            
                    @endswitch
                    
                    
        
                </ul>
            </div>
        </div>
    </div>
</div>
