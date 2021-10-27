<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research Citations') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->research_code])
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
                                <div class="alert alert-success alert-index mx-3">
                                    {{ $message }}
                                </div>
                                @endif
                            </div>
                            <div class="col-md-12 text-right">
                                <div class="dropdown">
                                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Options
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="{white-space: nowrap; }}">
                                        @switch($research->status_name)
                                            @case('New Commitment') @case('Ongoing')
                                                <a class="dropdown-item" href="{{ route('research.completed.create', $research->research_code) }}">Complete Research</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Research Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Completed')
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Update Complete Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code ) }}">Research Publication</a>
                                                <a class="dropdown-item" href="{{ route('research.presentation', $research->research_code ) }}">Research Presentation</a>
                                                <a class="dropdown-item" href="{{ route('research.copyright', $research->research_code ) }}">Research Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Research Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Completed & Published')
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Update Complete Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Update Research Publication</a>
                                                <a class="dropdown-item" href="{{ route('research.presentation', $research->research_code ) }}">Research Presentation</a>
                                                <a class="dropdown-item" href="{{ route('research.copyright', $research->research_code ) }}">Research Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.citation.create', $research->research_code) }}">Add Research Citation</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Research Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Completed & Presented')
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Update Complete Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code ) }}">Research Publication</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Update Research Presentation</a>
                                                <a class="dropdown-item" href="{{ route('research.copyright', $research->research_code ) }}">Research Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Research Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Completed, Presented, Published')
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Update Complete Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Update Research Publication</a>
                                                <a class="dropdown-item" href="{{ route('research.presentation', $research->research_code) }}">Update Research Presentation</a>
                                                <a class="dropdown-item" href="{{ route('research.copyright', $research->research_code ) }}">Research Copyright</a>
                                                <a class="dropdown-item" href="{{ route('research.citation.create', $research->research_code) }}">Add Research Citation</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Research Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Deferred')
                                                @break
                                            @default
                                                
                                        @endswitch
                                        <a class="dropdown-item" href="{{ route('research.edit', $research->research_code) }}">Update Research Info</a>
                                        <button class="dropdown-item text-danger " data-toggle="modal" data-target="#deleteModal">Delete</button>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table my-3 text-center table-hover" id="researchc_table" >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Article Title</th>
                                                <th>Article Author</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($researchcitations as $citation)
                                                <tr role="button">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><a href="{{ route('research.citation.show', [$research->research_code, $citation->id]) }}" class="link text-dark">{{ $citation->article_title }}</a></td>
                                                    <td>{{ $citation->article_author }}</td>
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