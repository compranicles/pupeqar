{{-- Preview Modal --}}
<div class="modal fade" id="previewModal" data-backdrop="static" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Form Preview</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe src="{{ route('admin.fields.preview', $form_id) }}" id="previewFrame" class="embed-responsive-item" title="Form Preview"></iframe>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>