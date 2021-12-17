<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
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
                            <div class="offset-md-3 col-md-2 mt-2">
                                <label for="statusFilter">Select Research Status: </label>
                            </div>
                            <div class="col-md-4">
                                <select id="statusFilter" class="custom-select">
                                    <option value="">Show All</option>
                                    @foreach ($researchStatus as $status)
                                    <option value="{{ $status->name }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    
                                    <table class="table my-3 text-center table-hover" id="researchTable" >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Research Code</th>
                                                <th>Research Title</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($researches as $research)
                                                <tr role="button">
                                                    <td><a href="{{ route('research.show', $research->id) }}" class="link text-dark">{{ $loop->iteration }}</a></td>
                                                    <td>{{ $research->research_code }}</td>
                                                    <td>{{ $research->title }}</td>
                                                    <td>{{ $research->status_name }}</td>
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

            $("#statusFilter").change(function (e) {
                table.draw();
            });

            table.draw();

            $('#researchTable').on('click', 'tbody td', function(){
                window.location = $(this).closest('tr').find('td:eq(0) a').attr('href');
            });
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
