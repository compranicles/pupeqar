<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n3">
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('faculty.expert-service-as-consultant.index') }}" class="text-dark"  class="text-dark" :active="request()->routeIs('faculty.expert-service-as-consultant.*')">
                            {{ __('Expert Service Rendered as Consultant') }}
                        </x-jet-nav-link>
                    </li>

                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('colleges.index') }}" class="text-dark"  :active="request()->routeIs('colleges.*')">
                            {{ __('Expert Service Rendered in Conference, Workshops, and/or Training Course for Professional') }}
                        </x-jet-nav-link>
                    </li>
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('departments.index') }}" class="text-dark"  :active="request()->routeIs('departments.*')">
                            {{ __('Expert Service Rendered in Academic Journals/Books/Publication/Newsletter/Creative Works') }}
                        </x-jet-nav-link>
                    </li>
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('departments.index') }}" class="text-dark"  :active="request()->routeIs('departments.*')">
                            {{ __('Extension Service') }}
                        </x-jet-nav-link>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>