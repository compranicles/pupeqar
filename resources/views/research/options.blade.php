<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Options
    </button>
    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="{white-space: nowrap; }}">
    @if ($involvement != 11)
        <a class="dropdown-item" href="{{ route('research.edit', $research_id) }}">Edit Research Info</a>
        <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button>
    @else
        @switch($research_status)
            @case('26')
                <a class="dropdown-item" href="{{ route('research.edit', $research_id) }}">Edit Research Info</a>
                <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button>
                @break
            @case('27')
                <a class="dropdown-item" href="{{ route('research.completed.create', $research_id) }}">Mark as Completed</a>
                <a class="dropdown-item" href="{{ route('research.utilization.create', $research_id) }}">Add Utilization</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('research.edit', $research_id) }}">Edit Research Info</a>
                <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button>
                @break
            @case('28')
                <a class="dropdown-item" href="{{ route('research.publication', $research_id ) }}">Mark as Published</a>
                <a class="dropdown-item" href="{{ route('research.presentation', $research_id ) }}">Mark as Presented</a>
                <a class="dropdown-item" href="{{ route('research.utilization.create', $research_id) }}">Add Utilization</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('research.complete', $research_id) }}">Edit Completed Research</a>
                <a class="dropdown-item" href="{{ route('research.copyright', $research_id ) }}">Add/Edit Copyright</a>
                <a class="dropdown-item" href="{{ route('research.edit', $research_id) }}">Edit Research Info</a>
                <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button>
                @break
            @case('29')
                <a class="dropdown-item" href="{{ route('research.publication', $research_id ) }}">Mark as Published</a>
                <a class="dropdown-item" href="{{ route('research.utilization.create', $research_id) }}">Add Utilization</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('research.complete', $research_id) }}">Edit Completed Research</a>
                <a class="dropdown-item" href="{{ route('research.publication', $research_id) }}">Edit Presentation</a>
                <a class="dropdown-item" href="{{ route('research.copyright', $research_id ) }}">Add/Edit Copyright</a>
                <a class="dropdown-item" href="{{ route('research.edit', $research_id) }}">Edit Research Info</a>
                <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button>
                @break
            @case('30')
                <a class="dropdown-item" href="{{ route('research.presentation', $research_id ) }}">Mark as Presented</a>
                <a class="dropdown-item" href="{{ route('research.citation.create', $research_id) }}">Add Citation</a>
                <a class="dropdown-item" href="{{ route('research.utilization.create', $research_id) }}">Add Utilization</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('research.complete', $research_id) }}">Edit Completed Research</a>
                <a class="dropdown-item" href="{{ route('research.publication', $research_id) }}">Edit Publication</a>
                <a class="dropdown-item" href="{{ route('research.copyright', $research_id ) }}">Add/Edit Copyright</a>
                <a class="dropdown-item" href="{{ route('research.edit', $research_id) }}">Edit Research Info</a>
                <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button>
                @break
            @case('31')
                {{-- Presented.Published --}}
                <a class="dropdown-item" href="{{ route('research.citation.create', $research_id) }}">Add Citation</a>
                <a class="dropdown-item" href="{{ route('research.utilization.create', $research_id) }}">Add Utilization</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('research.complete', $research_id) }}">Edit Completion</a>
                <a class="dropdown-item" href="{{ route('research.publication', $research_id) }}">Edit Publication</a>
                <a class="dropdown-item" href="{{ route('research.presentation', $research_id) }}">Edit Presentation</a>
                <a class="dropdown-item" href="{{ route('research.copyright', $research_id ) }}">Add/Edit Copyright</a>
                <a class="dropdown-item" href="{{ route('research.edit', $research_id) }}">Edit Research Info</a>
                <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button>
                @break
            @case('32')
                {{-- Deffered --}}
                @can('defer', App\Models\Research::class)
                <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button>
                @endcan
                
                @break
            @default
                
        @endswitch
    @endif
    
        
    </div>
</div>