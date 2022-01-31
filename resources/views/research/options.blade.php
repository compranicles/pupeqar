<div class="dropdown">
    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Options
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton" style="{white-space: nowrap; }}">
    @if ($involvement != 11)
        <a class="dropdown-item" href="{{ route('research.edit', $research_id) }}">Edit Research Info</a>
        <button class="dropdown-item text-danger" href="{{ route('research.manage-researchers', $research->research_code) }}"
            data-toggle="modal" data-target="#removeModal">Remove Research</button>
        {{-- <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button> --}}
    @else
        {{-- <a class="dropdown-item" href="{{ route('research.manage-researchers', $research->research_code) }}">Manage Researchers</a> --}}
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

{{-- Remove Form Modal --}}
<div class="modal fade" id="removeModal" data-backdrop="static" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="removeModalLabel">Do you want to remove this accomplishment?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">No</button>
                <a href="{{ route('research.remove-self', $research_code) }}" class="btn btn-danger mb-2 mr-2">YES</a>
            </div>
        </div>
    </div>
</div>

 {{-- Delete Form Modal --}}
 <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Delete Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="text-center">Are you sure you want to delete this research?</h5>
                <p class="text-center h4">{{ $research->title }}</p>
                <form action="{{ route('research.destroy', $research->id) }}" method="POST">
                    @csrf
                    @method('delete')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger mb-2 mr-2">Delete</button>
            </form>
            </div>
        </div>
    </div>
</div>