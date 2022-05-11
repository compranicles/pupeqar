<div class="modal fade" id="addModal" data-backdrop="static" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Invite</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('extension.invite.add', $extension->id) }}" method="post">
                    @csrf
                    <select name="employees[]" id="employees" class="form-control custom-select" required>
                        
                        <option value="" selected disabled>Choose...</option>
                        @foreach ($allEmployees as $row)
                        <option value="{{ $row->id }}">{{ $row->last_name.", ".$row->first_name." ".$row->middle_name." ".$row->suffix }}</option>
                        @endforeach
                    </select>
            </div>
            <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success mb-2">Send Invitation</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('dist/selectize.min.js') }}"></script>
    <script>
        $("#employees").selectize({
            maxItems: null,
            sortField: "text",
        });
    </script>
    
@endpush