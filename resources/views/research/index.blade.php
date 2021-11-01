<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research') }}
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
                                <a href="{{ route('research.create') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Add
                                </a>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table my-3 text-center table-hover" id="research_table" >
                                        <thead>
                                            <tr>
                                                <th>Research Code</th>
                                                <th>Research Title</th>
                                                <th>Date Posted</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($researches as $research)
                                                <tr role="button">
                                                    <td><a href="{{ route('research.show', $research->research_code) }}" class="link text-dark">{{ $research->research_code }}</a></td>
                                                    <td>{{ $research->title }}</td>
                                                    <td>{{ $research->created_at }}</td>
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
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(function() {
            $("#research_table").DataTable();
            $('#research_table').on('click', 'tbody td', function(){
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
    <script>

    </script>
@endpush
</x-app-layout>