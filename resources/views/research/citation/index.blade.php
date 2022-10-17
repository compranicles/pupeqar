<x-app-layout>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->id, 'research_status' => $research->status])
            </div>
        </div>
        @if ($research->nature_of_involvement == 11)
            <div class="alert alert-info" role="alert-reminder">
                <i class="bi bi-lightbulb-fill"></i> <strong>Reminder: </strong>Click <strong>Tag Co-Researchers</strong> button to check if the co-researchers already confirm your shared research <strong>before submitting</strong>.
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="card h-100">
                    <div class="card-body">
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
                            </div>
                            <div class="col-md-6">
                                {{-- ADD Fields --}}
                                @if ($research->nature_of_involvement == 11 || $research->nature_of_involvement == 224)
                                    <a href="{{ route('research.citation.create', $research->id) }}" class="btn btn-success">
                                        <i class="fas fa-plus"></i> Add Citation
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
                                                <th>Article Title</th>
                                                <th>Article Author</th>
                                                <th>Quarter</th>
                                                <th>Year</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($researchcitations as $citation)
                                                <tr role="button">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><a href="{{ route('research.citation.show', [$research->id, $citation->id]) }}" class="link text-dark">{{ $citation->article_title }}</a></td>
                                                    <td>{{ $citation->article_author }}</td>
                                                    <td class="{{ ($citation->report_quarter == $currentQuarterYear->current_quarter && $citation->report_year == $currentQuarterYear->current_year) ? 'to-submit' : '' }}">
                                                        {{ $citation->report_quarter }}
                                                    </td>
                                                    <td>
                                                        {{ $citation->report_year }}
                                                    </td>
                                                    <td>
                                                        @if ($submissionStatus[5][$citation->id] == 0)
                                                            <a href="{{ url('submissions/check/5/'.$citation->id) }}" class="btn btn-sm btn-primary">Submit</a>
                                                        @elseif ($submissionStatus[5][$citation->id] == 1)
                                                            <a href="{{ url('submissions/check/5/'.$citation->id) }}" class="btn btn-sm btn-success">Submitted {{ $submitRole[$citation->id] == 'f' ? 'as Faculty' : 'as Admin' }}</a>
                                                        @elseif ($submissionStatus[5][$citation->id] == 2)
                                                            <a href="{{ route('research.citation.edit', [$research->id, $citation->id]) }}#upload-document" class="btn btn-sm btn-warning d-inline-flex align-items-center"><i class="bi bi-exclamation-circle-fill text-danger mr-1"></i> No Document</a>
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
                    <form action="{{ route('research.destroy', $research->research_code) }}" method="POST">
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
            $('#researchc_table').on('click', 'tbody td', function(){
                window.location = $(this).closest('tr').find('td:eq(1) a').attr('href');
            });
        });
    </script>
@endpush
</x-app-layout>
