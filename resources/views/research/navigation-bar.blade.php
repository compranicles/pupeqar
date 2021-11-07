<div class="card mb-3 research-tabs">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n3">
                    @switch($research_status)
                        @case('26') 
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.show', $research_code) }}" class="text-dark" :active="request()->routeIs('research.show')">
                                    {{ __('Registration') }}
                                </x-jet-nav-link>
                            </li>
                            @break
                        @case('27')
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.show', $research_code) }}" class="text-dark" :active="request()->routeIs('research.show')">
                                    {{ __('Registration') }}
                                </x-jet-nav-link>
                            </li>
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.utilization.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.utilization.*')">
                                    {{ __('Utilization') }}
                                </x-jet-nav-link>
                            </li>
                            @break
                        @case('28')
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.show', $research_code) }}" class="text-dark" :active="request()->routeIs('research.show')">
                                    {{ __('Registration') }}
                                </x-jet-nav-link>
                            </li>
        
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.completed.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.completed.*')">
                                    {{ __('Completion') }}
                                </x-jet-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.copyrighted.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.copyrighted.*')">
                                    {{ __('Copyright') }}
                                </x-jet-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.utilization.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.utilization.*')">
                                    {{ __('Utilization') }}
                                </x-jet-nav-link>
                            </li>
                            
                            @break
                        @case('29')
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.show', $research_code) }}" class="text-dark" :active="request()->routeIs('research.show')">
                                    {{ __('Registration') }}
                                </x-jet-nav-link>
                            </li>
        
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.completed.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.completed.*')">
                                    {{ __('Completion') }}
                                </x-jet-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.presentation.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.presentation.*')">
                                    {{ __('Presentation') }}
                                </x-jet-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.copyrighted.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.copyrighted.*')">
                                    {{ __('Copyright') }}
                                </x-jet-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.utilization.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.utilization.*')">
                                    {{ __('Utilization') }}
                                </x-jet-nav-link>
                            </li>
                            @break
                        @case('30')
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.show', $research_code) }}" class="text-dark" :active="request()->routeIs('research.show')">
                                    {{ __('Registration') }}
                                </x-jet-nav-link>
                            </li>
        
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.completed.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.completed.*')">
                                    {{ __('Completion') }}
                                </x-jet-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.copyrighted.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.copyrighted.*')">
                                    {{ __('Copyright') }}
                                </x-jet-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.publication.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.publication.*')">
                                    {{ __('Publication') }}
                                </x-jet-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.citation.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.citation.*')">
                                    {{ __('Citation') }}
                                </x-jet-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.utilization.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.utilization.*')">
                                    {{ __('Utilization') }}
                                </x-jet-nav-link>
                            </li>
                            @break
                        @case('31')
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.show', $research_code) }}" class="text-dark" :active="request()->routeIs('research.show')">
                                    {{ __('Registration') }}
                                </x-jet-nav-link>
                            </li>
        
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.completed.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.completed.*')">
                                    {{ __('Completion') }}
                                </x-jet-nav-link>
                            </li>
                            
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.publication.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.publication.*')">
                                    {{ __('Publication') }}
                                </x-jet-nav-link>
                            </li>
        
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.presentation.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.presentation.*')">
                                    {{ __('Presentation') }}
                                </x-jet-nav-link>
                            </li>
                            
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.copyrighted.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.copyrighted.*')">
                                    {{ __('Copyrighted') }}
                                </x-jet-nav-link>
                            </li>

                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.citation.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.citation.*')">
                                    {{ __('Citation') }}
                                </x-jet-nav-link>
                            </li>
        
                            <li class="nav-item">
                                <x-jet-nav-link href="{{ route('research.utilization.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.utilization.*')">
                                    {{ __('Utilization') }}
                                </x-jet-nav-link>
                            </li>
                            @break
                        @case('32')
                            <li class="nav-item">
                                <x-jet-nav-link  href="{{ route('research.show', $research_code) }}" class="text-dark" :active="request()->routeIs('research.show')">
                                    {{ __('Registration') }}
                                </x-jet-nav-link>
                            </li>
                            @break
                    
                        @default
                            
                    @endswitch
                    
                    
        
                </ul>
            </div>
        </div>
    </div>
</div>
