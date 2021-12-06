{{-- Add Modal --}}
<div class="modal fade" id="addModal" data-backdrop="static" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add Field</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('report-categories.report-columns.store', $report_category) }}" id="add_report_column" class="needs-validation" method="POST">
                    @csrf
                    <div class="row">

                        <div class="col-md-12">
                            {{-- Field Name Input --}}
                            <div class="form-group">
                                <x-jet-label value="{{ __('Column Name') }}" />
            
                                <input class="form-control" type="text" id="column_name" name="column_name" required>
                                        
                                <div class="invalid-feedback">
                                    This is required. 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Field Size Input --}}
                            <div class="form-group">
                                <x-jet-label value="{{ __('Table Reference') }}" />
        
                                <select name="table" id="table" class="form-control custom-select" required>
                                    <option value="" selected disabled>Choose...</option>
                                    <option value="expert_service_academics">expert_service_academics</option>
                                    <option value="expert_service_conferences">expert_service_conferences</option>
                                    <option value="expert_service_consultants">expert_service_consultants</option>
                                    <option value="extension_services">extension_services</option>
                                    <option value="inventions">inventions</option>
                                    <option value="mobilities">mobilities</option>
                                    <option value="partnerships">partnerships</option>
                                    <option value="references">references</option>
                                    <option value="research">research</option>
                                    <option value="research_citations">research_citations</option>
                                    <option value="research_completes">research_completes</option>
                                    <option value="research_copyrights">research_copyrights</option>
                                    <option value="research_documents">research_documents</option>
                                    <option value="research_presentations">research_presentations</option>
                                    <option value="research_publications">research_publications</option>
                                    <option value="research_utilizations">research_utilizations</option>
                                    <option value="syllabi">syllabi</option>
                                </select>
        
                                <div class="invalid-feedback">
                                    This is required. 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Field Type --}}
                            <div class="form-group">
                                <x-jet-label value="{{ __('Column Reference') }}" />

                                <select name="table_column" id="table_column" class="form-control custom-select" disabled required>
                                    <option value="" selected disabled>Choose...</option>
                                    
                                </select>

                                <div class="invalid-feedback">
                                    This is required. 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" id="addField" class="btn btn-success mb-2 mr-2">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $ (function (){
            $('#addModal').on('hidden.bs.modal', function() {
                $('#add_report_column').get(0).reset();
                $('#table_column').attr('disabled', 'disabled');
            });
        });

        $('#table').on('change', function(){
            $('#table_column').removeAttr('disabled');
            var table = $('#table').val();
            $('#table_column').empty().append('<option selected="selected" disabled="disabled" value="">Choose...</option>');
            $.get('/reports/tables/'+table, function (data){
                // console.log(data);
                data.forEach(function (item){
                    $("#table_column").append(new Option(item, item));
                });
            });
        });

    </script>
@endpush