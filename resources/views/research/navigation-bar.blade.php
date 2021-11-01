<div class="card mb-3 research-tabs">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n3">

                    <li class="nav-item">
                        <x-jet-nav-link  id="link-to-complete" href="{{ route('research.show', $research_code) }}" class="text-dark" :active="request()->routeIs('research.show')">
                            {{ __('Registration') }}
                        </x-jet-nav-link>
                    </li>

                    <li class="nav-item">
                        <x-jet-nav-link  id="link-to-complete" href="{{ route('research.completed.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.completed.*')">
                            {{ __('Completion') }}
                        </x-jet-nav-link>
                    </li>
                    
                    <li class="nav-item">
                        <x-jet-nav-link id="link-to-publish" href="{{ route('research.publication.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.publication.*')">
                            {{ __('Publication') }}
                        </x-jet-nav-link>
                    </li>

                    <li class="nav-item">
                        <x-jet-nav-link  id="link-to-present" href="{{ route('research.presentation.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.presentation.*')">
                            {{ __('Presentation') }}
                        </x-jet-nav-link>
                    </li>

                    
                    <li class="nav-item">
                        <x-jet-nav-link id="link-to-copyright" href="{{ route('research.copyrighted.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.copyrighted.*')">
                            {{ __('Copyrighted') }}
                        </x-jet-nav-link>
                    </li>

                    <li class="nav-item">
                        <x-jet-nav-link id="link-to-cite" href="{{ route('research.citation.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.citation.*')">
                            {{ __('Citation') }}
                        </x-jet-nav-link>
                    </li>

                    <li class="nav-item">
                        <x-jet-nav-link id="link-to-utilize" href="{{ route('research.utilization.index', $research_code) }}" class="text-dark" :active="request()->routeIs('research.utilization.*')">
                            {{ __('Utilization') }}
                        </x-jet-nav-link>
                    </li>
                    
        
                </ul>
            </div>
        </div>
    </div>
</div>
