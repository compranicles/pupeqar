<x-app-layout>
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
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Home</a>
                            <a class="nav-link" id="nav-myreports-tab" data-toggle="tab" href="#nav-myreports" role="tab" aria-controls="nav-myreports" aria-selected="false">Denied Reports</a>
                        </div>
                    </nav>
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <h3 class="text-center">
                                        Quarterly Accomplishment Report
                                    </h3>
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 text-right">
                                    @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ $message }}
                                    </div>
                                    @endif
                                    @if (!empty(array_filter(collect($report_array)->toArray())) && !empty(array_filter(collect($report_document_checker)->toArray())))
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#submitReportModal" id="submitReport">Submit Report</button>
                                    @endif
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
        
                                    <div class="accordion" id="accordionReport">
                                        @foreach ( $report_tables as $table)
                                            @if (count($report_array[$table->id]) == 0)
                                                @continue
                                            @endif
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
                                                                        @forelse ($report_array[$table->id] as $row)
                                                                            <tr class="report-view" role="button" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.download', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                <td>{{ $row->research_code }}</td>
                                                                                <td>
                                                                                    @isset($row->id)
                                                                                        @if ( count($report_document_checker[$table->id][$row->id]) == 0)
                                                                                            <a href="{{ route('research.adddoc', [$row->id, $table->id]) }}" class="btn btn-sm btn-danger doc-incomplete">Missing Supporting Document</a>
                                                                                        @else
                                                                                            <span class="btn btn-sm btn-success doc-complete">Completed</span>
                                                                                        @endif
                                                                                    @else
                                                                                        @if ( count($report_document_checker[$table->id][$row->research_code]) == 0)
                                                                                            <a href="{{ route('research.adddoc', [$row->research_code, $table->id]) }}" class="btn btn-sm btn-danger doc-incomplete">Missing Supporting Document</a>
                                                                                        @else
                                                                                            <span class="btn btn-sm btn-success doc-complete">Completed</span>
                                                                                        @endif
                                                                                    @endisset
                                                                                </td>
                                                                            </tr>
                                                                        @empty
                                                                            <tr>
                                                                                <td colspan="3">Empty</td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                        @if (empty(array_filter(collect($report_array)->toArray())) && empty(array_filter(collect($report_document_checker)->toArray())))
                                            <h5 class="text-center">No Reports to Be Submitted</h5>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-myreports" role="tabpanel" aria-labelledby="nav-myreports-tab">
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <h3 class="text-center">
                                        Denied Reports
                                    </h3>
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responive text-center">
                                        <table class="table table-hover" id="report_denied">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>College</th>
                                                    <th>Department</th>
                                                    <th>Report Category</th>
                                                    <th>Date Reported</th>
                                                    <th>Status</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($reported_accomplishments as $row)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $row->college_name }}</td>
                                                    <td>{{ $row->department_name }}</td>
                                                    <td>{{ $row->report_category }}</td>
                                                    <td>{{ $row->report_date }}</td>
                                                    <td>
                                                        @if($row->chairperson_approval == 0)
                                                            <span class="btn btn-sm btn-danger text-dark">Denied by Chairperson</span>
                                                        @elseif ($row->dean_approval == 0)
                                                            <span class="btn btn-sm btn-danger">Denied by Dean/ Director</span>
                                                        @elseif ($row->sector_approval == 0)
                                                            <span class="btn btn-sm btn-success">Denied by Sector Head</span>
                                                        @elseif ($row->ipqmso_approval == 0)
                                                            <span class="btn btn-sm btn-danger text-dark">Denied by IPQMSO</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <button class="btn btn-sm btn-primary mb-1" id="view_accomp_details" data-id="{{ $row->id }}">View Reason</button>
                                                        <button class="btn btn-sm btn-secondary" id="view_accomp_documents" data-id="{{ $row->id }}">Edit</button>
                                                    </td>
                                                </tr>
                                                @empty
                                                    
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- VIew Report --}}
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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Submit Report --}}
<div class="modal fade" id="submitReportModal" tabindex="-1" aria-labelledby="submitReportLabel" aria-hidden="true">
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
                    <div class="col-md-12">Are You Sure?</div>
                </div>
                <form action="{{ route('faculty.store') }}" class="needs-validation" method="POST" novalidate>
                    @csrf
                    @foreach ( $report_tables as $table)
                        @foreach ($report_array[$table->id] as $row)
                            <input type="hidden" value="{{ ($row->research_code ?? '*').','.$table->id.','.($row->id ?? '*') }}" name="report_values[]">
                        @endforeach                        
                    @endforeach
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success mb-2 mr-2">Submit Report</button>
                </form>
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

            var countColumns = 0;
            var countValues = 0;
            $.get('/reports/tables/data/'+catID, function (data){
                data.forEach(function (item){
                    countColumns = countColumns + 1;
                    $('#columns_value_table').append('<tr id="row-'+countColumns+'" class="report-content"></tr>')
                    $('#row-'+countColumns).append('<td class="report-content font-weight-bold h5 text-right">'+item.name+':</td>');
                });
            });
            $.get('/reports/tables/data/'+catID+'/'+rowID, function (data){
                data.forEach(function (item){
                    countValues = countValues + 1;
                    if(item == null)
                        $('#row-'+countValues).append('<td class="report-content h5 text-left">-  </td>');
                    else
                        $('#row-'+countValues).append('<td class="report-content h5 text-left">'+item+'</td>');
                });
            });
            $.get('/reports/tables/data/documents/'+catID+'/'+rowID, function (data) {
                data.forEach(function (item){
                    var newlink = link.replace(':filename', item)
                    $('#data_documents').append('<a href="'+newlink+'" class="report-content h5 btn btn-success m-1">'+item+'<a/>');
                });
            });
        });


        $('#viewReport').on('hidden.bs.modal', function(event) {
            $('.report-content').remove();
        });

        $(function(){
            if( $('.doc-incomplete').length != 0)
                $('#submitReport').remove();
            $('#report_denied').DataTable();
        });
    </script>
@endpush

</x-app-layout>