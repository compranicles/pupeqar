<x-app-layout>
        @section('title', 'Research & Book Chapter |')
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Research & Book Chapter</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                {{-- Success Message --}}
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>
                @elseif ($message = Session::get('code-missing'))
                <div class="alert alert-danger alert-index">
                    {{ $message }}
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
                                {{-- ADD Fields --}}
                                <a href="{{ route('research.create') }}" class="btn btn-success mr-2">
                                    <i class="fas fa-plus"></i> Add Research
                                </a>
                                {{-- <button class="btn btn-primary mr-1" data-toggle="modal" data-target="#addModal">
                                     Use Research Code
                                </button> --}}
                                <button class="btn btn-primary mr-1" data-toggle="modal" data-target="#invitesModal">
                                    Research to Add (Tagged by your Lead) @if (count($invites) != 0)
                                                <span class="badge badge-secondary">{{ count($invites) }}</span>
                                            @else
                                                <span class="badge badge-secondary">0</span>
                                            @endif
                                </button>
                                <hr>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info" role="alert">
                                    <i class="bi bi-lightbulb-fill"></i> <strong>Instructions & Reminders: </strong> <br>
                                    <div class="ml-3">
                                        &#8226; Click on the row to View/Edit/Delete/Submit Research. <br>
                                        &#8226; Only the <strong>Lead Researcher</strong> can register the research. You must tag your co-researchers to share them the research you encode. <br>
                                        &#8226; If you are a <strong>Lead Researcher</strong>, tag your co-researchers before submitting. <br>
                                        <span class="ml-3"><i class="bi bi-arrow-right ml-1"></i></i> Click "Tag Co-researchers" button after you encode and view the research.</span><br>
                                        &#8226; If you are a <strong>Co-Researcher</strong>, check your <strong>notifications</strong> or click the "Research to Add" button to <strong>confirm and add</strong> the research registered by your Lead Researcher. <br>
                                        &#8226; Once you <strong>submit</strong> an accomplishment, you are <strong>not allowed to edit</strong> until the quarter period ends. <br>
                                        &#8226; Submit your accomplishments for the <strong>Quarter {{ $currentQuarterYear->current_quarter }}</strong> on or before 
                                            <?php
                                                $deadline = strtotime( $currentQuarterYear->deadline );
                                                $deadline = date( 'F d, Y', $deadline);
                                                ?>
                                                <strong>{{ $deadline }}</strong>.
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table my-3 table-hover" id="researchTable" >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Research Code</th>
                                                <th>Research Title</th>
                                                <th>Status</th>
                                                <th>College/Branch/ Campus/Office</th>
                                                <th>Date Modified</th>
                                                <th>Quarter</th>
                                                <th>Year</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($researches as $research)
                                                <tr role="button">
                                                    <td><a href="{{ route('research.show', $research->id) }}" class="link text-dark">{{ $loop->iteration }}</a></td>
                                                    <td>{{ $research->research_code }}</td>
                                                    <td>{{ $research->title }}</td>
                                                    <td>{{ $research->status_name }}</td>
                                                    <td>{{ $research->college_name }}</td>
                                                    <td>
                                                        <?php $updated_at = strtotime( $research->updated_at );
                                                            $updated_at = date( 'M d, Y h:i A', $updated_at ); ?>
                                                        {{ $updated_at }}
                                                    </td>
                                                    <td>{{ $research->report_quarter }}</td>
                                                    <td>{{ $research->report_year }}</td>
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

    @include('research.research-code')
    @include('research.invite-researchers.modal', compact('invites'))

    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $("#researchTable").dataTable();
    </script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert-index").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove();
            });
        }, 4000);
    </script>
    <script>
         $('#researchTable').on('click', 'tbody td', function(){
                window.location = $(this).closest('tr').find('td:eq(0) a').attr('href');
            });
    </script>
    <script>
        var max = new Date().getFullYear();
        var min = 0;
        var diff = max-2019;
        min = max-diff;
        select = document.getElementsByClassName('yearFilter');

        var status = {!! json_encode($statusResearch) !!};
        for (var sel = 0; sel < select.length; sel++) {
            for (var i = max; i >= min; i--) {
                select[sel].append(new Option(i, i));
                if (sel == 1 && i == "{{$year}}" && status == "started") {
                    document.getElementById("startFilter").value = i;
                }
                if (sel == 2 && i == "{{$year}}" && status == "completion") {
                    document.getElementById("completeFilter").value = i;
                }
                if (sel == 3 && i == "{{$year}}" && status == "published") {
                    document.getElementById("publishFilter").value = i;
                }
                if (sel == 4 && i == "{{$year}}" && status == "presented") {
                    document.getElementById("presentFilter").value = i;
                }
            }
        }
    </script>
    <script>
        $('#startFilter').on('change', function () {
            var year_started = $('#startFilter').val();
            var link = "{{ url('/research/filterByYear/:year/:status') }}";
            var newLink = link.replace(':year', year_started).replace(':status', 'started');
            window.location.replace(newLink);
        });
        $('#completeFilter').on('change', function () {
            var year_completed = $('#completeFilter').val();
            var link = "{{ url('/research/filterByYear/:year/:status') }}";
            var newLink = link.replace(':year', year_completed).replace(':status', 'completion');
            window.location.replace(newLink);
        });
        $('#publishFilter').on('change', function () {
            var year_published = $('#publishFilter').val();
            var link = "{{ url('/research/filterByYear/:year/:status') }}";
            var newLink = link.replace(':year', year_published).replace(':status', 'published');
            window.location.replace(newLink);
        });
        $('#presentFilter').on('change', function () {
            var year_presented = $('#presentFilter').val();
            var link = "{{ url('/research/filterByYear/:year/:status') }}";
            var newLink = link.replace(':year', year_presented).replace(':status', 'presented');
            window.location.replace(newLink);
        });
    </script>
@endpush

</x-app-layout>
