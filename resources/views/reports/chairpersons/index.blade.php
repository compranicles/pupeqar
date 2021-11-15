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
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center">
                                Quarterly Accomplishment Report - {{ $departmentHeadOf->department_name }}
                            </h3>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <nav>
                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                    <a class="nav-link active" id="nav-review-tab" data-toggle="tab" href="#nav-review" role="tab" aria-controls="nav-review" aria-selected="true">To Review</a>
                                    <a class="nav-link" id="nav-denied-tab" data-toggle="tab" href="#nav-denied" role="tab" aria-controls="nav-denied" aria-selected="false">Denied</a>
                                </div>
                            </nav>
                            <div class="tab-content" id="nav-tabContent">
                                <div class="tab-pane fade show active" id="nav-review" role="tabpanel" aria-labelledby="nav-review-tab">
                                    {{-- To Review Table --}}
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover table-bordered text-center" id="to_review_table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Report Category</th>
                                                            <th>Faculty</th>
                                                            <th>Report Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($reportsToReview as $row)
                                                            <tr>
                                                                <td>{{ $loop->iteration }}</td>
                                                                <td>{{ $row->report_category }}</td>
                                                                <td>{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                                                <td>{{ date( "F j, Y, g:i a", strtotime($row->created_at)) }}</td>
                                                                <td>
                                                                    <button class="btn btn-primary btn-sm button-view" id="viewButton" data-url="{{ route('document.download', ':filename') }}" data-accept="{{ route('chairperson.accept', ':id') }}" data-deny="{{ route('chairperson.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport">View</button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="nav-denied" role="tabpanel" aria-labelledby="nav-denied-tab">
                                    <div class="row">
                                        <div class="col-md-12 mt-3">
                                            <h3 class="text-center">
                                                Denied Reports
                                            </h3>
                                            <hr>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responive">
                                                <table class="table table-hover table-sm table-bordered text-center" id="report_denied">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Report Category</th>
                                                            <th>Faculty</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($reportsDenied as $row)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $row->report_category }}</td>
                                                            <td>{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                                           
                                                            <td>
                                                                <button class="btn btn-sm btn-primary button-deny" id="view_accomp_deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">View Reason</button>
                                                                <a href="{{ route('chairperson.relay', $row->id) }}" class="btn btn-sm btn-success" id="relay">Relay</a>
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
        $('.button-view').on('click', function(){
            var catID = $(this).data('id');
            var link = $(this).data('url');
            var accept = $(this).data('accept');
            var deny = $(this).data('deny');
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
                    $('#data_documents').append('<a href="'+newlink+'" class="report-content h5 m-1 btn btn-primary">'+item+'<a/>');
                });
            });
            
            $('#review_btn_accept').append('<a href="'+accept.replace(':id', catID)+'" class="btn btn-success btn-lg btn-block report-content">ACCEPT</a>');
            $('#review_btn_reject').append('<a href="'+deny.replace(':id', catID)+'" class="btn btn-danger  btn-lg btn-block report-content">DENY</a>');
            
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


        $(function () {
            $('#to_review_table').DataTable();
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