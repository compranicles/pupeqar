<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Report Types') }}
        </h2>
    </x-slot>
     
    <div class="container mt-n4">
        
        <div class="row mt-3 mb-3">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>
            <div class="col-md-12 d-flex">
                <h2 class="font-weight-bold mb-2">Report Templates</h2>
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
                                <div class="table-responsive">
                                    <table id="report_type_table" class="table table-hover table-sm text-center">
                                        <thead>
                                            <tr>
                                                <th>Report Format</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($report_types as $type)
                                            <tr>
                                                <td>{{ $type->name }}</td>
                                                <td>
                                                    <a href="{{ route('maintenance.generate.view', $type->id) }}" class="btn btn-primary btn-sm">View Report Categories</a>
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
            $("#report_type_table").dataTable();
        </script>
    @endpush
</x-app-layout>