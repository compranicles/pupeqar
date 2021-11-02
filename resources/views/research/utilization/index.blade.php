<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research Utilizations') }}
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
                                            @case('Ongoing')
                                                <a class="dropdown-item" id="to-complete" href="{{ route('research.completed.create', $research->research_code) }}">Mark as Completed</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                @break
                                            @case('Completed')
                                                <a class="dropdown-item" id="to-publish" href="{{ route('research.publication', $research->research_code ) }}">Mark as Published</a>
                                                <a class="dropdown-item" id="to-present" href="{{ route('research.presentation', $research->research_code ) }}">Mark as Presented</a>
                                                @if ($copyrighted == 0)
                                                    <a class="dropdown-item" id="to-copyright" href="{{ route('research.copyright', $research->research_code ) }}">Add Copyright</a>
                                                @endif
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Edit Completed Research</a>
                                                @if ($copyrighted == 1)
                                                    <a class="dropdown-item" id="to-copyright" href="{{ route('research.copyright', $research->research_code ) }}">Edit Copyright</a>
                                                @endif
                                                @break
                                            @case('Published')
                                                <a class="dropdown-item" id="to-present" href="{{ route('research.presentation', $research->research_code ) }}">Mark as Presented</a>
                                                @if ($copyrighted == 0)
                                                    <a class="dropdown-item" id="to-copyright" href="{{ route('research.copyright', $research->research_code ) }}">Add Copyright</a>
                                                @endif
                                                <a class="dropdown-item" href="{{ route('research.citation.create', $research->research_code) }}">Add Citation</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Edit Completed Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Edit Publication</a>
                                                @if ($copyrighted == 1)
                                                    <a class="dropdown-item" id="to-copyright" href="{{ route('research.copyright', $research->research_code ) }}">Edit Copyright</a>
                                                @endif
                                                @break
                                            @case('Presented')
                                                
                                                <a class="dropdown-item" id="to-publish" href="{{ route('research.publication', $research->research_code ) }}">Mark as Published</a>
                                                @if ($copyrighted == 0)
                                                    <a class="dropdown-item" id="to-copyright" href="{{ route('research.copyright', $research->research_code ) }}">Add Copyright</a>
                                                @endif
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Edit Completed Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Edit Presentation</a>
                                                @if ($copyrighted == 1)
                                                    <a class="dropdown-item" id="to-copyright" href="{{ route('research.copyright', $research->research_code ) }}">Edit Copyright</a>
                                                @endif
                                                @break
                                            @case('Presented & Published')
                                                @if ($copyrighted == 0)
                                                    <a class="dropdown-item" id="to-copyright" href="{{ route('research.copyright', $research->research_code ) }}">Add Copyright</a>
                                                @endif
                                                <a class="dropdown-item" href="{{ route('research.citation.create', $research->research_code) }}">Add Citation</a>
                                                <a class="dropdown-item" href="{{ route('research.utilization.create', $research->research_code) }}">Add Utilization</a>
                                                <div class="dropdown-divider"></div>
                                                <a class="dropdown-item" href="{{ route('research.complete', $research->research_code) }}">Edit Completed Research</a>
                                                <a class="dropdown-item" href="{{ route('research.publication', $research->research_code) }}">Edit Publication</a>
                                                <a class="dropdown-item" href="{{ route('research.presentation', $research->research_code) }}">Edit Presentation</a>
                                                @if ($copyrighted == 1)
                                                    <a class="dropdown-item" id="to-copyright" href="{{ route('research.copyright', $research->research_code ) }}">Edit Copyright</a>
                                                @endif
                                                @break
                                            @case('Deferred')
                                                @break
                                            @default
                                                
                                        @endswitch
                                        <a class="dropdown-item" href="{{ route('research.edit', $research->research_code) }}">Edit Research Info</a>
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
                                                <th>Agency/Organization that Utilized the Research Output</th>
                                                <th>Brief Description of Research Utilization</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($researchutilizations as $utilization)
                                                <tr role="button">
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td><a href="{{ route('research.utilization.show', [$research->research_code, $utilization->id]) }}" class="link text-dark">{{ $utilization->organization }}</a></td>
                                                    <td>{{ $utilization->utilization_description }}</td>
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
            var rowCount = $('#researchc_table tbody tr').length;
            console.log(rowCount);
            if(rowCount == 1){
                
            }
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
        <script>
        $(function() {
            $('#link-to-register').show();
            $('#link-to-utilize').show();

            $('#link-to-complete').show();
            $("#link-to-publish").show();
            $("#link-to-present").show();
            $("#link-to-copyright").show();
            $("#link-to-cite").show();
        });

        if ( {{$research->status}} ==26 ){
            $('.research-tabs').remove();
        }

        else if ({{ $research->status }} == 27) {
            if ({{ $utilized }} == 0) {
                // $('#link-to-register').show();
                // $('#link-to-utilize').show();
                $('#link-to-utilize').remove();
            }
            else {
                $('.research-tabs').remove();
            }
        }

        else if ({{ $research->status }} == 28) {
            $("#link-to-cite").remove();

            if ({{ $published }} == 0) {
                $("#link-to-publish").remove();
            }

            if ({{ $presented }} == 0) {
                $("#link-to-present").remove();
            }

            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }

        else if ({{ $research->status }} == 29) {
            $("#link-to-cite").remove();

            if ({{ $published }} == 0) {
                $("#link-to-publish").remove();
            }

            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }

        else if ({{ $research->status }} == 30) {
            if ({{ $presented }} == 0) {
                $("#link-to-present").remove();
            }

            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $cited }} == 0) {
                $("#link-to-cite").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }

        else if ({{$research->status}} == 31) {
            if ({{ $copyrighted }} == 0) {
                $("#link-to-copyright").remove();
            }

            if ({{ $cited }} == 0) {
                $("#link-to-cite").remove();
            }

            if ({{ $utilized }} == 0) {
                $("#link-to-utilize").remove();
            }
        }
    </script>
@endpush
</x-app-layout>