<button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button>
<div class="dropdown">
    <button class="btn btn-warning btn-sm dropdown-toggle py-3" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Other Options
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
        {{-- <a class="dropdown-item" href="{{ route('research.manage-researchers', $research->research_code) }}">Manage Researchers</a> --}}
        @switch($research_status)
            @case('27')
                <a class="dropdown-item" href="{{ route('research.completed.create', $research_id) }}"><i class="bi bi-check2-circle"></i> Mark as Completed</a>
                <div class="dropdown-divider"></div>
                @break
            @case('28')
            <a class="dropdown-item" href="{{ route('research.presentation', $research_id ) }}"><i class="bi bi-laptop"></i> Mark as Presented</a>
                <a class="dropdown-item" href="{{ route('research.publication', $research_id ) }}"><i class="bi bi-paperclip"></i> Mark as Published</a>
                @break
            @case('29')
                <a class="dropdown-item" href="{{ route('research.publication', $research_id ) }}"><i class="bi bi-paperclip"></i> Mark as Published</a>
                @break
            @case('30')
                <a class="dropdown-item" href="{{ route('research.presentation', $research_id ) }}"><i class="bi bi-laptop"></i> Mark as Presented</a>
                <a class="dropdown-item" href="{{ route('research.citation.create', $research_id) }}"><i class="bi bi-blockquote-left"></i> Add Citation</a>
                @break
                @case('31')
                {{-- Presented.Published --}}
                <a class="dropdown-item" href="{{ route('research.citation.create', $research_id) }}"><i class="bi bi-blockquote-left"></i> Add Citation</a>
                @break
                @default
            @endswitch
        <a class="dropdown-item" href="{{ route('research.utilization.create', $research_id) }}"><i class="bi bi-gear"></i> Add Utilization</a>
        @if ($involvement != 224 && $research_id == $firstResearch[$research_id])
            <div class="dropdown-divider"></div>
            <a class="dropdown-item {{$submissionStatus[1][$research_id] == 1 ? 'disabled' : '' }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tooltip on bottom" href="{{ route('research.invite.index', $research->id) }}">Tag Coresearchers</a>
        @endif
    </div>
</div>
<div class="dropdown">
    <button class="btn btn-primary btn-sm dropdown-toggle py-3" type="button" id="submitDropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Submit
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="submitDropdownMenuButton">
        <!-- Submit buttons -->
        @if ($submissionStatus[1][$research->id] == 0)
            <a href="{{ url('submissions/check/1/'.$research->id) }}" class="dropdown-item">Submit Registration Record</a>
        @elseif ($submissionStatus[1][$research->id] == 1)
            <a href="{{ url('submissions/check/1/'.$research->id) }}" class="dropdown-item text-success">Registration Submitted {{ $submitRole[$research->id] == 'f' ? 'as Faculty' : 'as Admin' }}</a>
        @elseif ($submissionStatus[1][$research->id] == 2)
            <a href="{{ route('research.edit', $research->id) }}#upload-document" class="dropdown-item"><i class="bi bi-exclamation-circle-fill text-danger"></i> Registration - No Document</a>
        @else
            -
        @endif 
        <!-- Completion -->
        @if ($completionRecord[$research->id] != null)
            @if ($submissionStatus[2][$completionRecord[$research->id]['id']] == 0)
                <a href="{{ url('submissions/check/2/'.$completionRecord[$research->id]['id']) }}" class="dropdown-item">Submit Completion Record</a>
            @elseif ($submissionStatus[2][$completionRecord[$research->id]['id']] == 1)
                <a href="{{ url('submissions/check/2/'.$completionRecord[$research->id]['id']) }}" class="dropdown-item text-success">Completion Submitted {{ $submitRole[$completionRecord[$research->id]['id']] == 'f' ? 'as Faculty' : 'as Admin' }}</a>
            @elseif ($submissionStatus[2][$completionRecord[$research->id]['id']] == 2)
                <a href="{{ route('research.complete', $completionRecord[$research->id]['id']) }}#upload-document" class="dropdown-item"><i class="bi bi-exclamation-circle-fill text-danger"></i> Completion - No Document</a>
            @else
                -
            @endif
        @endif
        <!-- Presentation -->
        @if ($presentationRecord[$research->id] != null)
            @if ($submissionStatus[4][$presentationRecord[$research->id]['id']] == 0)
                <a href="{{ url('submissions/check/4/'.$presentationRecord[$research->id]['id']) }}" class="dropdown-item">Submit Presentation Record</a>
            @elseif ($submissionStatus[4][$presentationRecord[$research->id]['id']] == 1)
                <a href="{{ url('submissions/check/4/'.$presentationRecord[$research->id]['id']) }}" class="dropdown-item text-success">Presentation Submitted {{ $submitRole[$presentationRecord[$research->id]['id']] == 'f' ? 'as Faculty' : 'as Admin' }}</a>
            @elseif ($submissionStatus[4][$presentationRecord[$research->id]['id']] == 2)
                <a href="{{ route('research.complete', $presentationRecord[$research->id]['id']) }}#upload-document" class="dropdown-item"><i class="bi bi-exclamation-circle-fill text-danger"></i> Presentation - No Document</a>
            @else
                -
            @endif
        @endif
        <!-- Publication -->
        @if ($publicationRecord[$research->id] != null)
            @if ($submissionStatus[3][$publicationRecord[$research->id]['id']] == 0)
                <a href="{{ url('submissions/check/3/'.$publicationRecord[$research->id]['id']) }}" class="dropdown-item">Submit Publication Record</a>
            @elseif ($submissionStatus[3][$publicationRecord[$research->id]['id']] == 1)
                <a href="{{ url('submissions/check/3/'.$publicationRecord[$research->id]['id']) }}" class="dropdown-item text-success">Publication Submitted {{ $submitRole[$publicationRecord[$research->id]['id']] == 'f' ? 'as Faculty' : 'as Admin' }}</a>
            @elseif ($submissionStatus[3][$publicationRecord[$research->id]['id']] == 2)
                <a href="{{ route('research.complete', $publicationRecord[$research->id]['id']) }}#upload-document" class="dropdown-item"><i class="bi bi-exclamation-circle-fill text-danger"></i> Publication - No Document</a>
            @else
                -
            @endif
        @endif
        <!-- Copyright -->
        @if ($copyrightRecord[$research->id] != null)
            @if ($submissionStatus[7][$copyrightRecord[$research->id]['id']] == 0)
                <a href="{{ url('submissions/check/7/'.$copyrightRecord[$research->id]['id']) }}" class="dropdown-item">Submit Copyright Record</a>
            @elseif ($submissionStatus[7][$copyrightRecord[$research->id]['id']] == 1)
                <a href="{{ url('submissions/check/7/'.$copyrightRecord[$research->id]['id']) }}" class="dropdown-item text-success"> Copyright Submitted {{ $submitRole[$copyrightRecord[$research->id]['id']] == 'f' ? 'as Faculty' : 'as Admin' }}</a>
            @elseif ($submissionStatus[7][$copyrightRecord[$research->id]['id']] == 2)
                <a href="{{ route('research.complete', $copyrightRecord[$research->id]['id']) }}#upload-document" class="dropdown-item"><i class="bi bi-exclamation-circle-fill text-danger"></i> Copyright - No Document</a>
            @else
                - 
            @endif  
        @endif
        <a class="dropdown-item" href="{{ route('research.citation.index', $research->id) }}">Submit Citations</a>
        <a class="dropdown-item" href="{{ route('research.utilization.index', $research->id) }}">Submit Utilizations</a>
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