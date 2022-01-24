<x-app-layout>
    <x-slot name="header">
        @include('submissions.navigation', ['roles' => $roles, 'departments' => $departments_nav, 'colleges' => $colleges_nav, 'sectors' => $sectors, 'departmentsExtension' => $departmentsExtension, 'departmentsResearch', $departmentsResearch])
    </x-slot>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h3 class="text-center">
                                Quarterly Accomplishment Report - Sector
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
                            <div class="d-flex flex-row justify-content-start">
                                <div class="col-md-3" style="display: none;" id="actionButtons">
                                    <button id="acceptButton" data-toggle="modal" data-target="#selectApprove" class="btn btn-success mr-2"><i class="bi bi-check2"></i> Receive</button>
                                    <button id="denyButton" data-toggle="modal" data-target="#selectDeny" class="btn btn-danger"><i class="bi bi-slash-circle"></i> Return</a>
                                </div>
                                <div class="col-md-9 ml-auto">
                                    <div class="d-flex justify-content-start">
                                        <label class="mt-2 mr-2" for="collegeFilter">College/Branch/Campus/Office:</label>
                                        <select id="collegeFilter" class="custom-select mr-2">
                                            <option value="">All</option>
                                            @foreach ($colleges as $college)
                                            <option value="{{ $college->name }}">{{ $college->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover" id="to_review_table">
                                    <thead>
                                        <tr>
                                            <th class="text-center"><input type="checkbox" id="select-all"></th>
                                            <th></th>
                                            <th>College/Branch/Campus/Office</th>
                                            <th>Accomplishment Report</th>
                                            <th>Employee</th>
                                            <th>Report Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reportsToReview as $row)
                                            <tr role="button">
                                                <td class="text-center"><input type="checkbox" class="select-box" data-id="{{ $row->id }}"></td>
                                                <td class="button-view" data-url="{{ route('document.download', ':filename') }}" data-accept="{{ route('sector.accept', ':id') }}" data-deny="{{ route('sector.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport">{{ $loop->iteration }}</td>
                                                <td class="button-view" data-url="{{ route('document.download', ':filename') }}" data-accept="{{ route('sector.accept', ':id') }}" data-deny="{{ route('sector.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport">{{ $row->college_name }}</td>
                                                <td class="button-view" data-url="{{ route('document.download', ':filename') }}" data-accept="{{ route('sector.accept', ':id') }}" data-deny="{{ route('sector.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport">{{ $row->report_category }}</td>
                                                <td class="button-view" data-url="{{ route('document.download', ':filename') }}" data-accept="{{ route('sector.accept', ':id') }}" data-deny="{{ route('sector.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport">{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.(($row->suffix == null) ? '' : ', '.$row->suffix) }}</td>
                                                <td class="button-view" data-url="{{ route('document.download', ':filename') }}" data-accept="{{ route('sector.accept', ':id') }}" data-deny="{{ route('sector.reject-create', ':id') }}" data-id="{{ $row->id }}" data-toggle="modal" data-target="#viewReport">{{ date( "F j, Y, g:i a", strtotime($row->created_at)) }}</td>
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
    </div>
</div>

<div class="modal fade" id="viewReport" tabindex="-1" aria-labelledby="viewReportLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="viewReportLabel">View Accomplishment</h5>
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
                <div class="col-md-11 h5 font-weight-bold">Documents:</div>
                <div class="col-md-11" id="data_documents">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="ml-auto" id="review_btn_reject">
            </div>
            <div class="ml-2" id="review_btn_accept">
            </div>
            <span style="display: inline-block;
                border-left: 1px solid #ccc;
                margin: 0px 20px 0px 20px;;
                height: 30px;"></span>
            <div class="">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="modal fade" id="selectDeny" tabindex="-1" aria-labelledby="selectDenyLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectDenyLabel">Return Selected</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">Are you sure you want to  <span class="text-danger font-weight-bold">RETURN</span> selected?</div>
                </div>
                <form action="{{ route('sector.deny-select') }}" method="POST">
                    @csrf
                    @foreach ($reportsToReview as $row)
                        <input class="report-{{ $row->id }}" type="hidden" value="{{ $row->id }}" name="report_id[]" disabled>
                    @endforeach                        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger mb-2 mr-2">YES</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="selectApprove" tabindex="-1" aria-labelledby="selectApproveLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="selectApproveLabel">Receive Selected</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">Are you sure you want to <span class="text-success font-weight-bold">RECEIVE</span> selected?</div>
                </div>
                <form action="{{ route('sector.accept-select') }}" method="POST">
                    @csrf
                    @foreach ($reportsToReview as $row)
                        <input class="report-{{ $row->id }}" type="hidden" value="{{ $row->id }}" name="report_id[]" disabled>
                    @endforeach                        
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-success mb-2 mr-2">YES</button>
                </form>
            <div>
        </div>
    </div>
</div>

@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('#select-all').on('click', function(){
            if(this.checked){
                $('.select-box').prop('checked', true);
                $('.select-box').each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('.report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('.report-'+inputId).attr('disabled', true);
                    }
                });
            }
            else{
                $('.select-box').prop('checked', false);
                $('.select-box').each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('.report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('.report-'+inputId).attr('disabled', true);
                    }
                });
            }

            var allChecked = 0;
            $(".select-box").each(function(index, element){
                if(this.checked){
                    allChecked++;
                } 
            });
            if(allChecked == 0){
                $('#select-all').prop('checked', false);
                $('#actionButtons').hide();
            }
            else{
                $('#actionButtons').show();
            }
        });
        $('.select-box').on('click', function(){
            var inputId = $(this).data('id');

            if(this.checked){
                $('.report-'+inputId).removeAttr('disabled');
            }
            else{
                $('.report-'+inputId).attr('disabled', true);
            }

            var allChecked = 0;
            var flag = true;
            $(".select-box").each(function(index, element){
                if(this.checked){
                    allChecked++;
                } 
                else{
                    flag = false;
                }
            });
            if(allChecked == 0){
                $('#select-all').prop('checked', false);
                $('#actionButtons').hide();
            }
            else{
                $('#actionButtons').show();
            }
            if(flag == true){
                $('#select-all').prop('checked', true);
            }else{
                $('#select-all').prop('checked', false);
            }


        });

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
                    $('#row-'+countColumns).append('<td class="report-content font-weight-bold">'+k+'</td>');
                    $('#row-'+countColumns).append('<td class="report-content text-left">'+data[k]+'</td>');
                });
            });
            $.get('/reports/docs/'+catID, function (data) {
                data.forEach(function (item){
                    var newlink = link.replace(':filename', item)
                    $('#data_documents').append('<a href="'+newlink+'" class="report-content h5 m-1 btn btn-dark">'+item+'<a/>');
                });
            });
            
            $('#review_btn_reject').append('<a href="'+deny.replace(':id', catID)+'" class="btn btn-danger report-content"><i class="bi bi-slash-circle"></i> Return</a>');
            $('#review_btn_accept').append('<a href="'+accept.replace(':id', catID)+'" class="btn btn-success report-content"><i class="bi bi-check2"></i> Receive</a>');
            
        });

        $('#viewReport').on('hidden.bs.modal', function(event) {
            $('.report-content').remove();
        });

        $(function () {
            var table = $('#to_review_table').DataTable({
                order: [[1, 'asc']],
                columnDefs: [ {
                    targets: 0,
                    orderable: false
                } ]
            });
            
            var allChecked = 0;
            $(".select-box").each(function(index, element){
                if(this.checked){
                    allChecked++;
                } 
            });
            if(allChecked == 0){
                $('#select-all').prop('checked', false);
                $('#actionButtons').hide();
            }
            else{
                $('#actionButtons').show();
            }

            var collegeIndex = 0;
            $("#to_review_table th").each(function (i) {
                if ($($(this)).html() == "College/Branch/Campus/Office") {
                    collegeIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#collegeFilter').val()
                    var college = data[collegeIndex];
                    if (selectedItem === "" || college.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            $("#collegeFilter").change(function (e) {
                table.draw();
            });

            // $("#employeeFilter").change(function (e) {
            //     table.draw();
            // });

            table.draw();
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