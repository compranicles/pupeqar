<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->id, 'research_status' => $research->status])
            </div>
        </div>
        @if ($research->id == $firstResearch['id'])
        <div class="alert alert-info" role="alert-reminder">
            <i class="bi bi-lightbulb-fill"></i> <strong>Reminder: </strong><strong>Before you submit, click Tag Co-Researchers</strong> button to check if the co-researchers already confirm your shared research.
        </div>
        @endif
        <h4>Research Utilizations</h4>
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
                            <div class="col-md-6">
                                {{-- ADD Fields --}}
                                @if ($research->id == $firstResearch['id'])
                                <a href="{{ route('research.utilization.create', $research->id) }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add Utilization
                                </a>
                                @endif
                            </div>
                            <div class="col-md-6 text-md-right">
                                @include('research.options', ['research_id' => $research->id, 'research_status' => $research->status, 'involvement' => $research->nature_of_involvement, 'research_code' => $research->research_code])
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
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
                                            @foreach ($researchutilizations as $utilization)
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
                                                    @if ($submissionStatus[6][$utilization->id] == 0)
                                                        <a href="{{ url('submissions/check/6/'.$utilization->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                    @elseif ($submissionStatus[6][$utilization->id] == 1)
                                                        <a href="{{ url('submissions/check/6/'.$utilization->id) }}" class="btn btn-sm btn-success">Submitted {{ $submitRole[$utilization->id] == 'f' ? 'as Faculty' : 'as Admin' }}</a>
                                                    @elseif ($submissionStatus[6][$utilization->id] == 2)
                                                        <a href="{{ route('research.utilization.edit', [$research->id, $utilization->id]) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
                                                    @endif        
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
            var rowCount = $('#researchc_table tbody tr').length;
            console.log(rowCount);
            if(rowCount == 1){

            }
            $('#researchc_table').on('click', 'tbody td', function(){
                window.location = $(this).closest('tr').find('td:eq(1) a').attr('href');
            });
        });
    </script>
@endpush
</x-app-layout>
