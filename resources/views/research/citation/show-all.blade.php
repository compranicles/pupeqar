<x-app-layout>
    <div class="container">
        @section('title', 'Research/Book Chapter Citations |')
        <div class="row">
            <div class="col-md-12">
                <h3 class="font-weight-bold mr-2">Citations of {{ $research->title }}</h3>
                <div class="mb-3">
                    <a class="back_link" href="{{ route('research.index') }}"><i class="bi bi-chevron-double-left"></i>Back to Research Main Page</a>
                </div>
            </div>
        </div>
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
                        </div>
                        <div class="row">
                            <div class="col-md-12">
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
                                            @foreach ($citationRecords as $citation)
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
                                                        <div class="btn-group" role="group" aria-label="button-group">
                                                            <a href="{{ route('research.citation.edit', [$research->id, $citation->id]) }}" class="btn btn-sm btn-warning d-inline-flex align-items-center">Edit</a>
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
        });
    </script>
@endpush
</x-app-layout>
