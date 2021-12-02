<div class="card mb-3">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav justify-content-center m-n3">
                    @can('viewAny', App\Models\ExpertServiceConsultant::class)
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('faculty.expert-service-as-consultant.index') }}" class="text-dark"  class="text-dark" :active="request()->routeIs('faculty.expert-service-as-consultant.*')">
                            {{ __('Expert Service Rendered as Consultant') }}
                        </x-jet-nav-link>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\ExpertServiceConference::class)
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('faculty.expert-service-in-conference.index') }}" class="text-dark"  :active="request()->routeIs('faculty.expert-service-in-conference.*')">
                            {{ __('Expert Service Rendered in Conference, Workshops, and/or Training Course for Professional') }}
                        </x-jet-nav-link>
                    </li>
                    @endcan

                    @can('viewAny', App\Models\ExpertServiceAcademic::class)
                    <li class="nav-item">
                        <x-jet-nav-link href="{{ route('faculty.expert-service-in-academic.index') }}" class="text-dark"  :active="request()->routeIs('faculty.expert-service-in-academic.*')">
                            {{ __('Expert Service Rendered in Academic Journals/Books/Publication/Newsletter/Creative Works') }}
                        </x-jet-nav-link>
                    </li>
                    @endcan
                </ul>
            </div>
        </div>
    </div>
</div>