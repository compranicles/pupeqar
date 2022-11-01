<x-app-layout>
    <div class="container">
        @section('title', 'Research/Book Chapter Utilizations |')
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Utilizations of {{ $research->title }}</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('research.index') }}"><i class="bi bi-chevron-double-left"></i>Back to Research Main Page</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {{-- Success Message --}}
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>
                @endif
                @if ($message = Session::get('cannot_access'))
                    <div class="alert alert-danger alert-index">
                        {{ $message }}
                    </div>
                @endif
                <div class="card h-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table my-3 text-center table-hover" id="researchc_table" >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Agency/Organization that Utilized the Research Output</th>
                                                <th>Brief Description of Research Utilization</th>
                                                <th>Quarter</th>
                                                <th>Year</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($utilizationRecords as $utilization)
                                                <tr role="button">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><a href="{{ route('research.utilization.show', [$research->id, $utilization->id]) }}" class="link text-dark">{{ $utilization->organization }}</a></td>
                                                    <td>{{ $utilization->utilization_description }}</td>
                                                    <td class="{{ ($utilization->report_quarter == $currentQuarterYear->current_quarter && $utilization->report_year == $currentQuarterYear->current_year) ? 'to-submit' : '' }}">
                                                        {{ $utilization->report_quarter}}
                                                    </td>
                                                    <td>
                                                        {{ $utilization->report_year}}
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group" aria-label="button-group">
                                                            <a href="{{ route('research.utilization.edit', [$research->id, $utilization->id]) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
                                                            <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteModal">Delete</button> 
                                                        </div>     
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
    {{-- Delete Form Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="text-center">Are you sure you want to delete this research?</h5>
                    <form action="{{ route('research.destroy', $research->id) }}" method="POST">
                        @csrf
                        @method('delete')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger mb-2 mr-2">Delete</button>
                </form>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function() {
            $("#researchc_table").DataTable();
        });
    </script>p
@endpush
</x-app-layout>
