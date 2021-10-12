<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
                <h5 class="text-center">Are you sure you want to delete this form?</h5>
                <p id="itemToDelete" class="text-center h4"></p>
                <form action="{{ route('admin.colleges.destroy', ':id') }}" id="college_delete" method="POST">
                    @csrf
                    @method('delete')
            </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary mb-2" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger mb-2 mr-2">Delete</button>
        </form>
        </div>
    </div>
</div>