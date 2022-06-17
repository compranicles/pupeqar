{{-- Submit Report --}}
<div class="modal fade" id="submitReport" tabindex="-1" aria-labelledby="submitReportLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="submitReportLabel">Submit</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" id="confirm_message">Confirm to submit?</div>
                    <div class="col-md-12" id="already_submitted">Already submitted the accomplishment.</div>
                    <div class="col-md-12" id="missing_documents">Supporting documents are missing.</div>
                </div>
                <form action="{{ route('to-finalize.store') }}" class="needs-validation" method="POST" novalidate>
                    @csrf
                    <input id="accomplishment_input" type="hidden" value="" name="report_values[]">
            </div>
                {{-- {{ ($row->research_code ?? '*').','.$table->id.','.($row->id ?? '*').','.($row->research_id ?? '*') }} --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" id="submit_report" class="btn btn-success mb-2 mr-2">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).on('click', '.report-submit', function() {
        let repCat = $(this).data('category');
        let accId = $(this).data('id');
        let resCode = $(this).data('code');
        let resId = $(this).data('resid');
        let link = $(this).data('url');

        $.get(link, function (data){
            if (data == 1) {
                $('#submit_report').hide();
                $('#confirm_message').hide();
                $('#missing_documents').hide();
                $('#already_submitted').show();
                $('#accomplishment_input').attr('disabled', 'disabled');
            }
            else if (data == 2) {
                $('#submit_report').hide();
                $('#confirm_message').hide();
                $('#missing_documents').show();
                $('#already_submitted').hide();
                $('#accomplishment_input').attr('disabled', 'disabled');
            }
            else if (data == 3) {
                $('#submit_report').show();
                $('#confirm_message').show();
                $('#missing_documents').hide();
                $('#already_submitted').hide();
                $('#accomplishment_input').removeAttr('disabled');
                $('#accomplishment_input').val(resCode+','+repCat+','+accId+','+resId);
            }
        });
    });
</script>

@endpush
