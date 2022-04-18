<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research') }}
        </h2>
    </x-slot>

    {{-- Success Message --}}
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-index">
        <i class="bi bi-check-circle"></i> {{ $message }}
    </div>
    @elseif ($message = Session::get('code-missing'))
    <div class="alert alert-danger alert-index">
        {{ $message }}
    </div>
    @endif
    @if ($message = Session::get('cannot_access'))
        <div class="alert alert-danger alert-index">
            {{ $message }}
        </div>
    @endif
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                {{-- ADD Fields --}}
                                <a href="{{ route('research.create') }}" class="btn btn-success mr-2">
                                    <i class="fas fa-plus"></i> Add Research
                                </a>
                                {{-- <button class="btn btn-primary mr-1" data-toggle="modal" data-target="#addModal">
                                     Use Research Code
                                </button> --}}
                                <button class="btn btn-primary mr-1" data-toggle="modal" data-target="#invitesModal">
                                    Invites @if (count($invites) != 0)
                                                <span class="badge badge-secondary">{{ count($invites) }}</span>
                                            @else
                                                <span class="badge badge-secondary">0</span>
                                            @endif
                                </button>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="statusFilter" class="mr-2">Current Status: </label>
                                    <select id="statusFilter" class="custom-select">
                                        <option value="">Show All</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="reportFilter" class="mr-2">Year Registered:</label>
                                    <div class="d-flex">
                                        <select id="reportFilter" class="custom-select" name="reportFilter">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="quarterFilter" class="mr-2">Quarter Period: </label>
                                    <div class="d-flex">
                                        <select id="quarterFilter" class="custom-select" name="quarter">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="collegeFilter" class="mr-2">College/Branch/Campus/Office where committed: </label>
                                    <select id="collegeFilter" class="custom-select">
                                        <option value="">Show All</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="startFilter" class="mr-2">Year Started: <span style="color:red;">*</span></label>
                                    <div class="d-flex">
                                        <select id="startFilter" class="custom-select yearFilter" name="startFilter">
                                        <option value="started" {{ $year == "started" ? 'selected' : '' }} class="present_year">--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="completeFilter" class="mr-2">Year Completed: <span style="color:red;">*</span> </label>
                                    <div class="d-flex">
                                        <select id="completeFilter" class="custom-select yearFilter" name="completeFilter">
                                            <option value="completion" {{ $year == "completion" ? 'selected' : '' }} class="present_year">--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="publishFilter" class="mr-2">Year Published: <span style="color:red;">*</span> </label>
                                    <div class="d-flex">
                                        <select id="publishFilter" class="custom-select yearFilter" name="publishFilter">
                                            <option value="published" {{ $year == "published" ? 'selected' : '' }} class="present_year">--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="presentFilter" class="mr-2">Year Presented: <span style="color:red;">*</span> </label>
                                    <div class="d-flex">
                                        <select id="presentFilter" class="custom-select yearFilter" name="presentFilter">
                                            <option value="presented" {{ $year == "presented" ? 'selected' : '' }} class="present_year">--</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <small><span style="color:red;">*</span> Selects all records filtered by year.</small>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table my-3 table-hover" id="researchTable" >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Research Code</th>
                                                <th>Research Title</th>
                                                <th>Status</th>
                                                <th>College/Branch/Campus/Office</th>
                                                <th>Date Added</th>
                                                <th>Date Modified</th>
                                                <th>Quarter</th>
                                                <th>Year</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($researches as $research)
                                                <tr role="button">
                                                    <td><a href="{{ route('research.show', $research->id) }}" class="link text-dark">{{ $loop->iteration }}</a></td>
                                                    <td>{{ $research->research_code }}</td>
                                                    <td>{{ $research->title }}</td>
                                                    <td>{{ $research->status_name }}</td>
                                                    <td>{{ $research->college_name }}</td>
                                                    <td>
                                                        <?php $created_at = strtotime( $research->created_at );
                                                            $created_at = date( 'M d, Y h:i A', $created_at ); ?>  
                                                        {{ $created_at }}
                                                    </td>
                                                    <td>
                                                        <?php $updated_at = strtotime( $research->updated_at );
                                                            $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>  
                                                        {{ $updated_at }}
                                                    </td>
                                                    <td>{{ $research->report_quarter }}</td>
                                                    <td>{{ $research->report_year }}</td>
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

    @include('research.research-code')
    @include('research.invite-researchers.modal', compact('invites'))

    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#researchTable").dataTable();
        });
    </script>
    <script>
        var table =  $("#researchTable").DataTable({
                "searching":true,
                "searchCols": [
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    { "search": "{{ $currentQuarterYear->current_quarter }}" },
                    { "search": "{{ $currentQuarterYear->current_year }}" },
                ],
                initComplete: function () {
                    this.api().columns(3).every( function () {
                        var column = this;
                        var select = $('#statusFilter')
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
        
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
        
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    });
                    this.api().columns(4).every( function () {
                        var column = this;
                        var select = $('#collegeFilter')
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
        
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
        
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    });
                    this.api().columns(7).every( function () {
                        var column = this;
                        var select = $('#quarterFilter')
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
        
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
        
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    });

                    this.api().columns(8).every( function () {
                        var column = this;
                        var select = $('#reportFilter')
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );
        
                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );
        
                        column.data().unique().sort().each( function ( d, j ) {
                            select.append( '<option value="'+d+'">'+d+'</option>' )
                        } );
                    });
                }
            });

            var statusIndex = 0;
            $("#researchTable th").each(function (i) {
                if ($($(this)).html() == "Status") {
                    statusIndex = i; return false;

                }
            });

            $.fn.dataTable.ext.search.push(
                function (settings, data, dataIndex) {
                    var selectedItem = $('#statusFilter').val()
                    var status = data[statusIndex];
                    if (selectedItem === "" || status.includes(selectedItem)) {
                        return true;
                    }
                    return false;
                }
            );

            var collegeIndex = 0;
            $("#researchTable th").each(function (i) {
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

            var reportIndex = 0;
            $("#researchTable th").each(function (i) {
                if ($($(this)).html() == "Date Added") {
                    reportIndex = i; return false;

                }
            });
            

            $("#statusFilter").change(function (e) {
                table.draw();
            });

            $("#collegeFilter").change(function (e) {
                table.draw();
            });

            table.draw();
    </script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    <script>
         $('#researchTable').on('click', 'tbody td', function(){
                window.location = $(this).closest('tr').find('td:eq(0) a').attr('href');
            });
    </script>
    <script>
        var max = new Date().getFullYear();
        var min = 0;
        var diff = max-2019;
        min = max-diff;
        select = document.getElementsByClassName('yearFilter');

        var status = {!! json_encode($statusResearch) !!};
        for (var sel = 0; sel < select.length; sel++) {
            for (var i = max; i >= min; i--) {
                select[sel].append(new Option(i, i));
                if (sel == 1 && i == "{{$year}}" && status == "started") {
                    document.getElementById("startFilter").value = i;
                }
                if (sel == 2 && i == "{{$year}}" && status == "completion") {
                    document.getElementById("completeFilter").value = i;
                }
                if (sel == 3 && i == "{{$year}}" && status == "published") {
                    document.getElementById("publishFilter").value = i;
                }
                if (sel == 4 && i == "{{$year}}" && status == "presented") {
                    document.getElementById("presentFilter").value = i;
                }
            }
        }
    </script>
    <script>
        $('#startFilter').on('change', function () {
            var year_started = $('#startFilter').val();
            var link = "/research/filterByYear/:year/:status";
            var newLink = link.replace(':year', year_started).replace(':status', 'started');
            window.location.replace(newLink);
        });
        $('#completeFilter').on('change', function () {
            var year_completed = $('#completeFilter').val();
            var link = "/research/filterByYear/:year/:status";
            var newLink = link.replace(':year', year_completed).replace(':status', 'completion');
            window.location.replace(newLink);
        });
        $('#publishFilter').on('change', function () {
            var year_published = $('#publishFilter').val();
            var link = "/research/filterByYear/:year/:status";
            var newLink = link.replace(':year', year_published).replace(':status', 'published');
            window.location.replace(newLink);
        });
        $('#presentFilter').on('change', function () {
            var year_presented = $('#presentFilter').val();
            var link = "/research/filterByYear/:year/:status";
            var newLink = link.replace(':year', year_presented).replace(':status', 'presented');
            window.location.replace(newLink);
        });
    </script>
@endpush

</x-app-layout>