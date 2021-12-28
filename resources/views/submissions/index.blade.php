<x-app-layout>
    <x-slot name="header">
        @include('submissions.navigation', compact('roles', 'department_id', 'college_id'))
    </x-slot>
    <?php $ctr = 0; ?>
    @foreach ( $report_tables as $table)
        @if(array_key_exists($table->id, $report_array))
            @if (count($report_array[$table->id]) == 0)
                <?php $ctr = 0; ?>
            @else  
                <?php $ctr = 1; ?>
                @break
            @endif
        @endif
    @endforeach

    @if ($ctr == 0)
    <div class="row">
        <div class="col-md-12">
            <div class="alert alert-success text-center p-5" role="alert">
                <h5>
                    You have no new accomplishments to finalize so far.
                </h5> 
            </div>
        </div>
    </div>
    @else
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-success temp-alert">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>
                @endif
            </div>
        </div>
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12 ml-2">
                        <div class="submission-list">
                            <!-- <div class="row">
                                <div class="col-md-12 ml-2"> -->
                                        <ul class="nav justify-content-center m-n3">
                                            @foreach ( $report_tables as $table)
                                                @if(array_key_exists($table->id, $report_array))
                                                    @if (count($report_array[$table->id]) == 0)
                                                        @continue
                                                    @endif
                                                @endif
                                            <li class="nav-item">
                                                <x-jet-nav-link href="#{{$table->name}}" class="text-dark"  class="text-dark">
                                                    {{ __($table->name) }} <span class="badge bg-primary">{{ count($report_array[$table->id]) }}</span>
                                                </x-jet-nav-link>
                                            </li>
                                            @endforeach
                                        </ul>
                                <!-- </div>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        
        <button class="btn btn-primary mb-3 mt-3" data-toggle="modal" data-target="#submitReportModal" id="submitReport" style="width: 100%;">Submit</button>
        <input type="checkbox" id="all-submit" checked /> <label for="all-submit" class="font-weight-bold all-submit mt-1">Select All</label>
        <hr>
        @endif
        @php
            $tableCount = 1;
            $count = 1;
        @endphp
        @foreach ( $report_tables as $table)
            @if(!array_key_exists($table->id, $report_array))
                @continue
            @endif
            @if (count($report_array[$table->id]) == 0)
                @continue
            @endif
        <h3 id="{{ $table->name }}" class="submission-categories jumptarget">{{ $table->name }}</h3>
        <div class="card h-100 card-submission">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table table-hover submissions_table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="table-{{ $tableCount }}" class="select-submit-table" data-id='{{ $tableCount }}' checked></th>
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
                                        @else
                                        <th>ID</th>
                                        @endif
                                        <th>Date Last Accessed</th>
                                        <th>Reporting Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($report_array[$table->id] as $row)
                                        <tr role="button">
                                            <td><input type="checkbox" class="select-submit table-submit-{{ $tableCount }} all-submit" data-id='{{ $count }}' data-table-id={{ $tableCount }} 
                                                @isset($row->id)
                                                    @if ( count($report_document_checker[$table->id][$row->id]) == 0)
                                                        @if($table->id >= 1 && $table->id <= 7)
                                                        
                                                        @else
                                                        
                                                        @endif
                                                    @else
                                                        checked
                                                    @endif
                                                @else
                                                    @if ( count($report_document_checker[$table->id][$row->research_code]) == 0)
                                                        
                                                    @else
                                                        checked
                                                    @endif
                                                @endisset
                                                ></td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $loop->iteration }}</td>
                                            @if($table->id <= 7)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->research_code }}</td>
                                            @elseif(($table->id >= 8 && $table->id <= 10) || $table->id == 15)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title }}</td>
                                            @elseif($table->id == 12)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title_of_extension_program }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title_of_extension_project }}</td>
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title_of_extension_activity }}</td>
                                            @elseif($table->id == 13)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->title_of_partnership }}</td>
                                            @elseif($table->id == 14)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->host_name }}</td>
                                            @elseif($table->id == 16)
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->course_title }}</td>
                                            @else
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">{{ $row->id }}</td>
                                            @endif
                                            <td class="report-view" data-toggle="modal" data-target="#viewReport" data-id="{{ $table->id }}" data-url="{{ route('document.view', ':filename') }}" data-code="@isset($row->id){{ $row->id }}@else{{ $row->research_code }}@endisset">
                                                {{ date( 'M d, Y h:i A', strtotime($row->updated_at) ) }}
                                            </td>
                                            <td>
                                                @isset($row->id)
                                                    @if ( count($report_document_checker[$table->id][$row->id]) == 0)
                                                        @if($table->id >= 1 && $table->id <= 7)
                                                        <a href="{{ route('research.adddoc', [$row->id, $table->id]) }}" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @else
                                                        <a href="{{ route('submissions.faculty.adddoc', [$row->id, $table->id]) }}" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                        @endif
                                                    @else
                                                        <span class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</span>
                                                    @endif
                                                @else
                                                    @if ( count($report_document_checker[$table->id][$row->research_code]) == 0)
                                                        <a href="{{ route('research.adddoc', [$row->research_code, $table->id]) }}" class="badge rounded-pill bg-danger doc-incomplete" style="padding: 0.50rem; font-size: 0.75rem;">Missing Supporting Document</a>
                                                    @else
                                                        <span class="badge rounded-pill bg-success doc-complete" style="padding: 0.50rem; font-size: 0.75rem;">Completed</span>
                                                    @endif
                                                @endisset
                                            </td>
                                        </tr>
                                        @php
                                            $count++;
                                        @endphp
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
        @php
            $tableCount++;
        @endphp
        @endforeach
    </div>
    {{-- VIew Report --}}
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
                            <table class="table table-sm table-borderless table-hover" id="columns_value_table">
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="row justify-content-center">
                        <div class="col-md-11 h5 font-weight-bold">Documents</div>
                        <div class="col-md-11" id="data_documents">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                        <div class="col-md-12">Are you sure you want to submit? Your finalized accomplishment reports will proceed to the college/department/office.</div>
                    </div>
                    <form action="{{ route('to-finalize.store') }}" class="needs-validation" method="POST" novalidate>
                        @csrf
                        @php
                            $count2 = 1;
                        @endphp
                        @foreach ( $report_tables as $table)
                            @if(!array_key_exists($table->id, $report_array))
                                @continue
                            @endif
                            @foreach ($report_array[$table->id] as $row)
                                @isset($row->id)
                                    @if ( count($report_document_checker[$table->id][$row->id]) > 0)
                                        <input id="report-{{ $count2 }}" type="hidden" value="{{ ($row->research_code ?? '*').','.$table->id.','.($row->id ?? '*') }}" name="report_values[]">
                                    @endif
                                @else
                                    @if ( count($report_document_checker[$table->id][$row->research_code]) > 0)
                                        <input id="report-{{ $count2 }}" type="hidden" value="{{ ($row->research_code ?? '*').','.$table->id.','.($row->id ?? '*') }}" name="report_values[]">
                                    @endif
                                @endisset
                                @php
                                    $count2++;
                                @endphp
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

    <i class="bi bi-arrow-up-circle-fill" id="scrollUpButton" role="button" onclick="topFunction()"></i>
    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $('#adddocbutton').remove();
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
                    $('#row-'+countColumns).append('<td class="report-content font-weight-bold text-left">'+item.name+'</td>');
                });
            });
            $.get('/reports/tables/data/'+catID+'/'+rowID, function (data){
                data.forEach(function (item){
                    countValues = countValues + 1;
                    if(item == null)
                        $('#row-'+countValues).append('<td class="report-content text-left">-  </td>');
                    else
                        $('#row-'+countValues).append('<td class="report-content text-left">'+item+'</td>');
                });
            });
            $.get('/reports/tables/data/documents/'+catID+'/'+rowID, function (data) {
                if(data == false){
                    $('#data_documents').append('<a class="report-content btn-link text-dark">No Document Attached</a>');
                }
                else{
                    data.forEach(function (item){
                        var newlink = link.replace(':filename', item)
                            $('#data_documents').append('<a href="'+newlink+'" target="_blank" class="report-content btn btn-success m-1">'+item+'</a>');
                    });
                }
            });
            
        });

        $('.select-submit').on('click', function(){
            var inputId = $(this).data('id');
            var tableId = $(this).data('table-id');
            if(this.checked){
                $('#report-'+inputId).removeAttr('disabled');
            }
            else{
                $('#report-'+inputId).attr('disabled', true);
            }
            var allChecked = 0;
            var flag = true;

            $(".select-submit").each(function(index, element){
                if(this.checked){
                    allChecked++;
                    flag = true;
                } 
                else{
                    flag = false;
                    return false;
                }
            });
            if(allChecked == 0){
                $('#table-'+tableId).prop('checked', false);
            }
            if(flag == true){
                $('#table-'+tableId).prop('checked', true);
            }else{
                $('#table-'+tableId).prop('checked', false);
            }

            var allSubmitChecked = 0;
            var flagSubmit = true;
            $(".all-submit").each(function(index, element){
                if(this.checked){
                    allSubmitChecked++;
                    flagSubmit = true;
                } 
                else{
                    flagSubmit = false;
                    return false;
                }
            });
            if(allSubmitChecked == 0){
                $('#all-submit').prop('checked', false);
            }
            if(flagSubmit == true){
                $('#all-submit').prop('checked', true);
            }else{
                $('#all-submit').prop('checked', false);
            }
           
        });
        $('.select-submit-table').on('change', function(){
            var tableId = $(this).data('id');
            if(this.checked){
                $('.table-submit-'+tableId).prop('checked', true);
                $('.table-submit-'+tableId).each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('#report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('#report-'+inputId).attr('disabled', true);
                    }
                });
            }
            else{
                $('.table-submit-'+tableId).prop('checked', false);
                $('.table-submit-'+tableId).each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('#report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('#report-'+inputId).attr('disabled', true);
                    }
                });
            }

            var allChecked = 0;
            var flag = true;
            $(".select-submit-table").each(function(index, element){
                if(this.checked){
                    allChecked++;
                    flag = true;
                } 
                else{
                    flag = false;
                    return false;
                }
            });
            if(allChecked == 0){
                $('#all-submit').prop('checked', false);
            }
            if(flag == true){
                $('#all-submit').prop('checked', true);
            }else{
                $('#all-submit').prop('checked', false);
            }
        });
        $('#all-submit').on('click', function(){
            if(this.checked){
                $('.select-submit-table').prop('checked', true);
                $('.all-submit').prop('checked', true);
                $('.all-submit').each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('#report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('#report-'+inputId).attr('disabled', true);
                    }
                });
            }
            else{
                $('.select-submit-table').prop('checked', false);
                $('.all-submit').prop('checked', false);
                $('.all-submit').each(function(){
                    var inputId = $(this).data('id');
                    if(this.checked){
                        $('#report-'+inputId).removeAttr('disabled');
                    }
                    else{
                        $('#report-'+inputId).attr('disabled', true);
                    }
                });
            }
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
            if( ($('.doc-incomplete').length != 0) && ($('.doc-complete').length == 0)) {
                $('#submitReport').hide();
                $('#all-submit').hide();
                $('.all-submit').hide();
                $('.select-submit-table').hide();
                $('.select-submit').hide();

            }
            if(($('.doc-incomplete').length != 0) && ($('.doc-complete').length != 0)){
                $('#submitReport').show();
                
            }
            $('#report_denied').DataTable();
        });
    </script>
    <script>
        $(document).ready(function() {
          $('.submissions_table').DataTable({
            'aoColumnDefs': [{
                  'bSortable': false,
                  'aTargets': [0], /* 1st one, start by the right */
                  'searchable': false,
              }],
              "order": [],
        } );
        });
    </script>
    <script>
        var scrollUp = document.getElementById("scrollUpButton");
        // When the user scrolls down 20px from the top of the document, show the button
        window.onscroll = function() {scrollFunction()};

        function scrollFunction() {
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollUp.style.display = "block";
            } else {
                scrollUp.style.display = "none";
            }
        }

        // When the user clicks on the button, scroll to the top of the document
        function topFunction() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".temp-alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    @endpush

</x-app-layout>