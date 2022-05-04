<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __($research->research_code.' > Invite Co-Researchers') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('research.navigation-bar', ['research_code' => $research->id, 'research_status' => $research->status])
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
                @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>
                @endif
                @if ($message = Session::get('cannot_access'))
                    <div class="alert alert-danger alert-index">
                        {{ $message }}
                    </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Co-Researchers </h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        @include('research.options', ['research_id' => $research->id, 'research_status' => $research->status, 'involvement' => $research->nature_of_involvement, 'research_code' => $research->research_code])
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-0">
                                    <div class="d-flex align-items-baseline">
                                        <button class="btn btn-primary" data-toggle="modal" data-target="#addModal">
                                            <i class="fas fa-plus"></i> Invite
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12"> 
                                <div class="table-responsive">
                                    <table class="table table-bordered text-center" id="invite_table">
                                        <thead>
                                            <th>Name</th>
                                            <th>Nature of Involvement</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($coResearchers as $row)
                                                @if ($row->research_status == "0")
                                                    @continue
                                                @endif
                                            <tr>
                                                <td>{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->suffix }}</td>
                                                <td>
                                                    @if ($row->research_status == null)
                                                        N/A
                                                    @elseif($row->research_status == "1")
                                                        @if ($involvement[$row->id] == null)
                                                            N/A
                                                        @elseif ($involvement[$row->id] == "12")
                                                            Asst. Team Leader/ Co-Lead Researcher
                                                        @elseif ($involvement[$row->id] == "13")
                                                            Associate Researcher
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($row->research_status == null)
                                                        Pending...
                                                    @elseif($row->research_status == "1")
                                                        <span class="text-success">Accepted</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button class="remove_button btn btn-sm btn-danger"
                                                                data-toggle="modal" data-target="#removeResModal"
                                                                data-user-id="{{ $row->id }}" >
                                                        @if ($row->research_status == null)
                                                            Cancel
                                                        @elseif($row->research_status == "1")
                                                            Remove
                                                        @endif
                                                    </button>
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

    <div class="modal fade" id="removeResModal" data-backdrop="static" tabindex="-1" aria-labelledby="removeResModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeResModalLabel">Remove/Cancel <span class="font-weight-bold username"></span>?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('research.invite.remove', $research->id) }}" method="POST" >
                    @csrf

                    <input type="hidden" id="remove_id" name="user_id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger mb-2 mr-2">YES</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('research.invite-researchers.add', compact('allEmployees', 'research'))
   
@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>

    <script>
        var id;
        var name; 

        $("#invite_table").dataTable();

        $('.remove_button').on('click', function(){
            id = $(this).data('user-id');
            document.getElementById('remove_id').value = id;
        });

        $('#removeModal').on('hidden.bs.modal', function (){
            document.getElementById('remove_id').value = "";
        });

        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
@endpush

</x-app-layout>