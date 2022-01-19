<x-app-layout>   
    <x-slot name="header">
        @include('submissions.navigation', compact('roles', 'departments', 'colleges'))
    </x-slot>

    @if (in_array(5, $roles))
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5>Approved By Me</h5>
                        <hr>
                    </div>
                    <div class="col-md-12">
                        <div class="table-responive">
                            <table class="table table-hover table-sm" id="report_approved_by_me">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>Accomplishment Report</th>
                                        <th>Employee</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($approved_by_me as $row)
                                    <tr role="button">
                                        <td class="report-view button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></i></td>
                                        <td class="report-view text-center button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                        <td class="report-view button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                        <td class="report-view button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>

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
    @endif
    @if (in_array(6, $roles))
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Approved By Me</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive">
                        <table class="table table-hover table-sm" id="report_approved_by_me">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Department</th>
                                    <th>Accomplishment Report</th>
                                    <th>Employee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approved_by_me as $row)
                                <tr role="button">
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="button-view text-center" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->department_name }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
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
    @endif
    @if (in_array(7, $roles))
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Approved By Me</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive">
                        <table class="table table-hover table-sm" id="report_approved_by_me">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>College</th>
                                    <th>Accomplishment Report</th>
                                    <th>Employee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approved_by_me as $row)
                                <tr>
                                    <td class="text-center"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="button-view text-center" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->college_name }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
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
    @endif
    @if (in_array(8, $roles))
    <div class="card mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5>Approved By Me</h5>
                    <hr>
                </div>
                <div class="col-md-12">
                    <div class="table-responive">
                        <table class="table table-hover table-sm" id="report_approved_by_me">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>College</th>
                                    <th>Accomplishment Report</th>
                                    <th>Employee</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approved_by_me as $row)
                                <tr>
                                    <td class="text-center"><i class="bi bi-three-dots-vertical"></i></td>
                                    <td class="text-center button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $loop->iteration }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->college_name }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->report_category }}</td>
                                    <td class="button-view" data-toggle="modal" data-target="#viewReport"  data-url="{{ route('document.view', ':filename') }}" data-id="{{ $row->id }}">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
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
    @endif

    <div class="modal fade" id="viewReport" tabindex="-1" aria-labelledby="viewReportLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewReportLabel">View Submission</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row justify-content-center">
                    <div class="col-md-11">
                        <table class="table table-sm table-borderless" id="columns_value_table">
                        </table>
                    </div>
                </div>
                <hr>
                <div class="row justify-content-center">
                    <div class="col-md-11 h5 font-weight-bold">Documents</div>
                    <div class="col-md-11" id="data_documents">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-12 text-center" id="review_btn_undo">
                    </div>
                    <div class="col-md-12 text-center" id="review_btn_relay">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                
                var countColumns = 0;
                $.get('/reports/data/'+catID, function (data){
                    Object.keys(data).forEach(function(k){
                        countColumns = countColumns + 1;
                        $('#columns_value_table').append('<tr id="row-'+countColumns+'" class="report-content"></tr>')
                        $('#row-'+countColumns).append('<td class="report-content font-weight-bold">'+k+'</td>');
                        $('#row-'+countColumns).append('<td class="report-content text-left">'+data[k]+'</td>');
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
            $(function(){
                $('#report_approved_by_me').DataTable();
            });
            // auto hide alert
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 4000);
        </script>
    @endpush
</x-app-layout>