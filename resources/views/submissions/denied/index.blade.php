<x-app-layout>   
    <x-slot name="header">
            <a href="{{ route('to-finalize.index') }}" class="submission-menu {{ request()->routeIs('to-finalize.index') ? 'active' : ''}} ml-3">To Finalize</a>
            <a href="{{ route('submissions.denied.index') }}" class="submission-menu {{ request()->routeIs('submissions.denied.index') ? 'active' : ''}}">Denied</a>
    </x-slot>
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responive ">
                        <table class="table table-hover table-sm text-center" id="report_denied">
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
                                    <td>
                                        <?php $date_reported = strtotime( $row->report_date );
                                            $date_reported = date( 'M d, Y h:i A', $date_reported ); ?>
                                            {{ $date_reported }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-primary button-deny" id="view_accomp_deny" data-toggle="modal" data-target="#viewDeny" data-id="{{ $row->id }}">View Reason</button>
                                        <button class="btn btn-sm btn-secondary" id="view_accomp_documents" data-id="{{ $row->id }}">Manage</button>
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