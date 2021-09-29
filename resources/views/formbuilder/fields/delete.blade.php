 {{-- Delete Dropdown Modal --}}
 <div class="modal fade" id="deleteFieldModal" tabindex="-1" aria-labelledby="deleteFieldModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteFieldModalLabel">Delete Field</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="text-center">Are you sure you want to delete this field?</h5>
                <p id="field_name_delete" class="text-center h4"></p>
                <form action="#" data-action="{{ route('admin.forms.fields.destroy', ['form' => $form_id, 'field' => ':id']) }}" id="field_form_delete" method="POST">
                    @csrf
                    @method('delete')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger mb-2 mr-2" id="button_delete_field" data-id="#">Delete</button>
            </form>
            </div>
        </div>
    </div>
</div>