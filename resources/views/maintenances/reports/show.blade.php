<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Submission Tables') }}
        </h2>
    </x-slot>
     
    <div class="container mt-n4">
        
        <div class="row mt-3 mb-3">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>
            <div class="col-md-12 d-flex">
                <h2 class="font-weight-bold mb-2">Report Modals > Categories</h2>
                <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('report-types.index') }}">Report Modals</a></li>
                    <li class="breadcrumb-item active">Categories</li>
                </ol>
                </nav>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Success Message --}}
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                        @endif

                        {{-- Table for displaying --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive ">
                                    <table id="report_categories_table" class="table table-sm table-hover text-center">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Report Categories</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reportcategories as $reportcategory)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $reportcategory->name }}</td>
                                                <td>
                                                    <a href="{{ route('report-categories.show', $reportcategory->id) }}" class="btn btn-warning btn-sm">Manage Indicators/Columns</a>
                                                </td>    
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
            $("#report_categories_table").dataTable();
        </script>
    @endpush
</x-app-layout>