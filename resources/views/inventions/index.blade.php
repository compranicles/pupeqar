<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Invention') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                {{-- Success Message --}}
                                @if ($message = Session::get('success'))
                                <div class="alert alert-success alert-index mx-3">
                                    {{ $message }}
                                </div>
                                @endif
                            </div>
                            <div class="col-md-12">
                                {{-- ADD Fields --}}
                                <a href="{{ route('inventions.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add Invention
                                </a>
                                <hr>
                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="offset-md-3 col-md-2 mt-2">
                                <label for="statusFilter">Select Invention Status: </label>
                            </div>
                            <div class="col-md-4">
                                <select id="statusFilter" class="custom-select">
                                    <option value="">Show All</option>
                                    @foreach ($inventionsStatus as $status)
                                    <option value="{{ $status->name }}">{{ $status->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <hr>
                            </div>
                        </div> --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    
                                    <table class="table my-3 text-center table-hover" id="inventionsTable" >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Invention Title</th>
                                                <th>Date Modified</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($inventions as $invention)
                                                <tr role="button">
                                                    <td><a href="{{ route('inventions.show', $invention->invention_code) }}" class="link text-dark">{{ $loop->iteration }}</a></td>
                                                    <td>{{ $invention->title }}</td>
                                                    <td>{{ $invention->updated_at }}</td>
                                                    <td>{{ $invention->status_name }}</td>
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
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {

            $("#inventionsTable").dataTable({
                "searching":true
            });
            var table =  $("#inventionsTable").DataTable();
            // $("#inventionsTable_filter.dataTables_filter").append($("#status-filter"));

            var statusIndex = 0;
            $("#inventionsTable th").each(function (i) {
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

            $('#inventionsTable').on('click', 'tbody td', function(){
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
