r<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Reports') }}
        </h2>
    </x-slot>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center">
                                Quarterly Accomplishment Report - {{ $department_name }}
                            </h3>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="accordion" id="accordionReport">
                                @foreach ( $report_tables as $table)
                                <div class="card">
                                    <div class="card-header border @if ($loop->first) rounded-top @elseif ($loop->last) rounded-bottom @endif" id="heading-{{ $table->id }}">
                                        <h2 class="mb-0">
                                          <button class="btn btn-link btn-sm text-dark btn-block text-decoration-none font-weight-bold" type="button" data-toggle="collapse" data-target="#collapse-{{ $table->id }}" aria-expanded="true" aria-controls="collapse-{{ $table->id }}">
                                            {{ $table->name }}
                                          </button>
                                        </h2>
                                      </div>
                                  
                                      <div id="collapse-{{ $table->id }}" class="collapse @if ($loop->first) show @endif" aria-labelledby="heading-{{ $table->id }}" data-parent="#accordionReport">
                                        <div class="card-body border">
                                            <div class="row justify-content-center">
                                                <div class="col-md-12">
                                                    <div class="table-responsive text-center">
                                                        <table class="table table-sm table-bordered table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <td>#</td>
                                                                    <td>Research Code</td>
                                                                    <td>Reporting Status</td>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($report_array[$table->id] as $row)
                                                                    <tr class="report-view" role="button" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.download', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">
                                                                        <td>{{ $loop->iteration }}</td>
                                                                        <td>{{ $row->research_code }}</td>
                                                                        <td>
                                                                            @isset($row->id)
                                                                                @if ( count($report_document_checker[$table->id][$row->id]) == 0)
                                                                                    <span class="text-danger doc-incomplete">Missing Supporting Document</span>
                                                                                @else
                                                                                    <span class="text-success doc-complete">Completed</span>
                                                                                @endif
                                                                            @else
                                                                                @if ( count($report_document_checker[$table->id][$row->research_code]) == 0)
                                                                                    <span class="text-danger doc-incomplete">Missing Supporting Document</span>
                                                                                @else
                                                                                    <span class="text-success doc-complete">Completed</span>
                                                                                @endif
                                                                            @endisset
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="viewReport" tabindex="-1" aria-labelledby="viewReportLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewReportLabel">View Accomplishment</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-6 text-right" id="columns_view">
                    
                </div>
                <div class="col-md-6 text-left" id="data_view">

                </div>
            </div>
            <div class="row">
                <div class="col-md-12 h5 font-weight-bold text-center">Documents:</div>
                <div class="col-md-12 text-center" id="data_documents">

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
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('.report-view').on('click', function(){
            let catID = $(this).data('id');
            let rowID = $(this).data('code');
            let link = $(this).data('url');
            $.get('/reports/tables/data/'+catID, function (data){
                data.forEach(function (item){
                    $('#columns_view').append('<p class="report-content font-weight-bold h5 ">'+item.name+':</p>');
                });
            });
            $.get('/reports/tables/data/'+catID+'/'+rowID, function (data){
                data.forEach(function (item){
                    if(item == null)
                        $('#data_view').append('<p class="report-content h5">-  </p>');
                    else
                        $('#data_view').append('<p class="report-content h5">'+item+'</p>');
                });
            });
            $.get('/reports/tables/data/documents/'+catID+'/'+rowID, function (data) {
                data.forEach(function (item){
                    var newlink = link.replace(':filename', item)
                    $('#data_documents').append('<a href="'+newlink+'" class="report-content h5 btn btn-success">'+item+'<a/>');
                });
            });
        });

        $('#viewReport').on('hidden.bs.modal', function(event) {
            $('.report-content').remove();
        });
    </script>
@endpush

</x-app-layout>