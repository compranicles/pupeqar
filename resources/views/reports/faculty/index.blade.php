<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Review') }}
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
                            <a class="nav-link" id="nav-myreports-tab" data-toggle="tab" href="#nav-myreports" role="tab" aria-controls="nav-myreports" aria-selected="false">Denied</a>
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
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#submitReportModal" id="submitReport">Submit to Review</button>
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
                                                                            <th>#</th>
                                                                            @if($table->id <= 7)
                                                                            <th>Research Code</th>
                                                                            @elseif(($table->id >= 8 && $table->id <= 10) || $table->id == 13 || $table->id == 15)
                                                                            <th>Title</th>
                                                                            @elseif($table->id == 12)
                                                                            <th>Title of Extension Program</th>
                                                                            <th>Title of Extension Project</th>
                                                                            <th>Title of Extension Activity</th>
                                                                            @elseif($table->id == 14)
                                                                            <th>Host Name</th>
                                                                            @elseif($table->id == 16)
                                                                            <th>Course Title</th>
                                                                            @endif
                                                                            <th>Reporting Status</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @forelse ($report_array[$table->id] as $row)
                                                                            <tr>
                                                                                <td>{{ $loop->iteration }}</td>
                                                                                @if($table->id <= 7)
                                                                                <td>{{ $row->research_code }}</td>
                                                                                @elseif(($table->id >= 8 && $table->id <= 10) || $table->id == 15)
                                                                                <td>{{ $row->title }}</td>
                                                                                @elseif($table->id == 12)
                                                                                <td>{{ $row->title_of_extension_program }}</td>
                                                                                <td>{{ $row->title_of_extension_project }}</td>
                                                                                <td>{{ $row->title_of_extension_activity }}</td>
                                                                                @elseif($table->id == 13)
                                                                                <td>{{ $row->title_of_partnership }}</td>
                                                                                @elseif($table->id == 14)
                                                                                <td>{{ $row->host_name }}</td>
                                                                                @elseif($table->id == 16)
                                                                                <td>{{ $row->course_title }}</td>
                                                                                @endif
                                                                                <td>
                                                                                    <button class="report-view btn btn-sm btn-primary" role="button" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">View Data</button>
                                                                                    @isset($row->research_code)
                                                                                         @if ( count($report_document_checker[$table->id][$row->research_code]) == 0)
                                                                                            <a href="{{ route('research.adddoc', [$row->research_code, $table->id]) }}" class="btn btn-sm btn-danger doc-incomplete">Missing Supporting Document</a>
                                                                                        @else
                                                                                            <span class="btn btn-sm btn-success doc-complete">Completed</span>
                                                                                        @endif
                                                                                    @else
                                                                                        @if ( count($report_document_checker[$table->id][$row->id]) == 0)
                                                                                            @if($table->id >= 1 && $table->id <= 7)
                                                                                                <a href="{{ route('research.adddoc', [$row->id, $table->id]) }}" class="btn btn-sm btn-danger doc-incomplete">Missing Supporting Document</a>
                                                                                            @else
                                                                                                <a href="{{ route('faculty.adddoc', [$row->id, $table->id]) }}" class="btn btn-sm btn-danger doc-incomplete">Missing Supporting Document</a>
                                                                                            @endif
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
                                    <div class="table-responive ">
                                        <table class="table table-bordered table-hover table-sm text-center" id="report_denied">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>College</th>
                                                    <th>Department</th>
                                                    <th>Report Category</th>
                                                    <th>Date Reported</th>
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
                                                        <button class="btn btn-sm btn-primary button-deny" id="view_accomp_deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">View Reason</button>
                                                        <a href="{{ route('report.manage', [$row->id, $row->report_category_id]) }}" class="btn btn-sm btn-secondary" id="view_accomp_documents" data-id="{{ $row->id }}">View</a>
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
                        <table class="table table-sm table-borderless table-hover" id="columns_value_table">
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
                            @isset($row->research_code)
                                @if ( count($report_document_checker[$table->id][$row->research_code]) > 0)
                                    <input type="hidden" value="{{ ($row->research_code ?? '*').','.$table->id.','.($row->id ?? '*') }}" name="report_values[]">
                                @endif
                            @else
                                @if ( count($report_document_checker[$table->id][$row->id]) > 0)
                                    <input type="hidden" value="{{ ($row->research_code ?? '*').','.$table->id.','.($row->id ?? '*') }}" name="report_values[]">
                                @endif
                            @endisset
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

<div class="modal fade" id="viewDeny" tabindex="-1" aria-labelledby="viewDenyLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewDenyLabel">View Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-md-12 h4 font-weight-bold text-center">Denial Details:</div>
                <div class="col-md-12">
                    <table class="table table-sm table-borderless" id=" view_deny_table">
                        <tr id="deny-1">
                            <td class="text-right font-weight-bold h5">Denied By:</td>
                        </tr>
                        <tr id="deny-2">
                            <td class="text-right font-weight-bold h5">Date:</td>
                        </tr>
                        <tr id="deny-3">
                            <td class="text-right font-weight-bold h5">Reason:</td>
                        </tr>
                    </table>
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

            var countColumns = 0;
            var countValues = 0;
            $.get('/reports/tables/data/'+catID, function (data){
                data.forEach(function (item){
                    countColumns = countColumns + 1;
                    $('#columns_value_table').append('<tr id="row-'+countColumns+'" class="report-content"></tr>')
                    $('#row-'+countColumns).append('<td class="report-content font-weight-bold h5 text-left">'+item.name+':</td>');
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
                if(data == false){
                    $('#data_documents').append('<a class="report-content btn-link text-dark">No Document Attached</a>');
                }
                else{
                    data.forEach(function (item){
                        var newlink = link.replace(':filename', item)
                            $('#data_documents').append('<a href="'+newlink+'" target="_blank" class="report-content h5 btn btn-success m-1">'+item+'</a>');
                    });
                }
            });
        });


        $('.button-deny').on('click', function () {
            var catID = $(this).data('id');
            
            var countColumns = 1;
            $.get('/reports/reject-details/'+catID, function(data){
                $('#deny-'+countColumns).append('<td class="deny-details h5 text-left">'+data.position_name+'</td>');
                countColumns = countColumns + 1;
                $('#deny-'+countColumns).append('<td class="deny-details h5 text-left">'+data.time+'</td>');
                countColumns = countColumns + 1;
                $('#deny-'+countColumns).append('<td class="deny-details h5 text-left">'+data.reason+'</td>');
            });
        });

        $('#viewReport').on('hidden.bs.modal', function(event) {
            $('.report-content').remove();
        });

        $('#viewDeny').on('hidden.bs.modal', function(event) {
            $('.deny-details').remove();
        });

        $(function(){
            // if( $('.doc-incomplete').length != 0)
            //     $('#submitReport').remove();
            $('#report_denied').DataTable();
        });
    </script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
@endpush

</x-app-layout>