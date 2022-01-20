<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Return') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('dean.index') }}" class="btn btn-secondary">BACK</a>
                                <hr>
                            </div>
                        </div>
                        <form action="{{ route('dean.reject-selected') }}" method="post">
                            @csrf
                           
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        @foreach ($reportIds as $row)
                                        <hr>
                                            <input type="hidden" value="{{ $row }}" name="report_id[]">
                                            <button type="button" class="btn btn-primary button-view mb-2" id="viewButton" 
                                                data-url="{{ route('document.view', ':filename') }}" data-accept="{{ route('dean.accept', ':id') }}" 
                                                data-id="{{ $row }}" data-toggle="modal" 
                                                data-target="#viewReport">
                                                View Details
                                            </button>
                                            <br>
                                            <label>Reason:</label><span style='color: red'></span>
                                    
                                            <input type="text" class="form-control" name="reason_{{ $row }}">
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-0">
                                        <div class="d-flex justify-content-end align-items-baseline">
                                            <button type="submit" id="submit" class="btn btn-danger">RETURN</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="viewReport" tabindex="-1" aria-labelledby="viewReportLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReportLabel">View Accomplishment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 h4 font-weight-bold text-center">Accomplishment Details:</div>
                    <div class="col-md-12">
                        <table class="table table-sm table-borderless" id="columns_value_table">
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 h5 font-weight-bold text-center">Documents:</div>
                    <div class="col-md-12 text-center" id="data_documents">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12"><hr></div>
                    <div class="col-md-6 text-center" id="review_btn_accept">
                    </div>
                    <div class="col-md-6 text-center" id="review_btn_reject">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $('.button-view').on('click', function(){
                var catID = $(this).data('id');
                var link = $(this).data('url');
                var countColumns = 0;
                
                $.get('/reports/data/'+catID, function (data){
                    Object.keys(data).forEach(function(k){
                        countColumns = countColumns + 1;
                        $('#columns_value_table').append('<tr id="row-'+countColumns+'" class="report-content"></tr>')
                        $('#row-'+countColumns).append('<td class="report-content font-weight-bold h5 text-right">'+k+':</td>');
                        $('#row-'+countColumns).append('<td class="report-content h5 text-left">'+data[k]+'</td>');
                    });
                });
                $.get('/reports/docs/'+catID, function (data) {
                    data.forEach(function (item){
                        var newlink = link.replace(':filename', item)
                        $('#data_documents').append('<a href="'+newlink+'" target="_blank" class="report-content h5 m-1 btn btn-primary">'+item+'<a/>');
                    });
                });
            });

            $('#viewReport').on('hidden.bs.modal', function(event) {
                $('.report-content').remove();
            });
        </script>
        
    @endpush
</x-app-layout>