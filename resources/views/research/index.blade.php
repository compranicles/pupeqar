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
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                {{-- ADD Fields --}}
                                <a href="{{ route('research.create') }}" class="btn btn-success mr-1">
                                    <i class="fas fa-plus"></i> Add Research
                                </a>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                                     Use Research Code
                                </button>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="statusFilter" class="mr-2">Current Status: </label>
                                <select id="statusFilter" class="custom-select">
                                    <option value="">Show All</option>
                                    @foreach ($researchStatus as $status)
                                    <option value="{{ $status->name }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="collegeFilter" class="mr-2">College/Branch/Campus/Office where committed: </label>
                                <select id="collegeFilter" class="custom-select">
                                    <option value="">Show All</option>
                                    @foreach($research_in_colleges as $college)
                                    <option value="{{ $college->name }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <hr>
                        <form action="{{ route('research.filterByYear') }}" method="post">
                            @csrf
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <label for="startFilter" class="mr-2">Year Started: </label>
                                <div class="d-flex">
                                    <select id="startFilter" class="custom-select yearFilter" name="startFilter">
                                    <option value="0" class="present_year">--</option>
                                    </select>
                                    <button class="btn btn-secondary ml-1"><i class="bi bi-filter"></i></button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="completeFilter" class="mr-2">Year Completed: </label>
                                <div class="d-flex">
                                    <select id="completeFilter" class="custom-select yearFilter" name="completeFilter">
                                        <option value="0" class="present_year">--</option>
                                    </select>
                                    <button class="btn btn-secondary ml-1"><i class="bi bi-filter"></i></button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="publishFilter" class="mr-2">Year Published: </label>
                                <div class="d-flex">
                                    <select id="publishFilter" class="custom-select yearFilter" name="publishFilter">
                                        <option value="0" class="present_year">--</option>
                                    </select>
                                    <button class="btn btn-secondary ml-1"><i class="bi bi-filter"></i></button>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label for="presentFilter" class="mr-2">Year Presented: </label>
                                <div class="d-flex">
                                    <select id="presentFilter" class="custom-select yearFilter" name="presentFilter">
                                        <option value="0" class="present_year">--</option>
                                    </select>
                                    <button class="btn btn-secondary ml-1"><i class="bi bi-filter"></i></button>
                                </div>
                            </div>
                            <div class="col-md-3 mt-auto">
                                <a type="button" href="{{ route('research.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i> Reset</a>
                            </div>
                        </div>
                    </form>
                        
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
    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {

            $("#researchTable").dataTable({
                "searching":true
            });
            var table =  $("#researchTable").DataTable();

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

            $("#statusFilter").change(function (e) {
                table.draw();
            });

            $("#collegeFilter").change(function (e) {
                table.draw();
            });

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
    <script>
         $('#researchTable').on('click', 'tbody td', function(){
                window.location = $(this).closest('tr').find('td:eq(0) a').attr('href');
            });
    </script>
    <script>
        var max = new Date().getFullYear(),
            min = max-2
            select = document.getElementsByClassName('yearFilter');
            // select2 = document.getElementById('completeFilter');

        for (var sel = 0; sel < select.length; sel++) {
            for (var i = max; i >= min; i--) {
                var opt = document.createElement('option');
                opt.value = i;
                opt.innerHTML = i;
                    select[sel].appendChild(opt);
            }
        }

        if ({{$yearStarted}} != 0) {
            if ($("#startFilter option").val("{{$yearStarted}}") != false) {
                $('#startFilter option:contains(' + "{{$yearStarted}}" + ')').prop({selected: true});
            }
        }
        if ({{$yearCompleted}} != 0) {
            if ($("#completeFilter option").val("{{$yearCompleted}}") != false) {
                $('#completeFilter option:contains(' + "{{$yearCompleted}}" + ')').prop({selected: true});
            }
        }
        if ({{$yearPublished}} != 0) {
            if ($("#publishFilter option").val("{{$yearPublished}}") != false) {
                $('#publishFilter option:contains(' + "{{$yearPublished}}" + ')').prop({selected: true});
            }
        }
        if ({{$yearPresented}} != 0) {
            if ($("#presentFilter option").val("{{$yearPresented}}") != false) {
                $('#presentFilter option:contains(' + "{{$yearPresented}}" + ')').prop({selected: true});
            }
        } 
    
    </script>
@endpush

</x-app-layout>