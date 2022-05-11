<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Invite Co-Extensionists') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <p class="mr-auto">
                <a class="back_link" href="{{ route('extension-service.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Extension Services</a>
            </p>
            <p class="mr-auto">
                <a class="back_link" href="{{ route('extension-service.show', $extension->id) }}"><i class="bi bi-chevron-double-left"></i>Back to the accomplishment</a>
            </p>
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
                                <h4>Co-Extensionists </h4>
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
                                            @foreach ($coExtensionists as $row)
                                                @if ($row->extension_status == "0")
                                                    @continue
                                                @endif
                                            <tr>
                                                <td>{{ $row->last_name.', '.$row->first_name.' '.$row->middle_name.' '.$row->suffix }}</td>
                                                <td>
                                                    @if ($row->extension_status == null)
                                                        n/a
                                                    @elseif($row->extension_status == "1")
                                                        @if ($involvement[$row->id] == null)
                                                            n/a
                                                        @elseif ($involvement[$row->id] == "108")
                                                            Facilitator
                                                        @elseif ($involvement[$row->id] == "109")
                                                            Resource Speaker
                                                        @elseif ($involvement[$row->id] == "110")
                                                            Organizer
                                                        @elseif ($involvement[$row->id] == "111")
                                                            Extensionist
                                                        @endif
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($row->extension_status == null)
                                                        Pending...
                                                    @elseif($row->extension_status == "1")
                                                        <span class="text-success">Accepted</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($row->is_owner == false)
                                                    <button class="remove_button btn btn-sm btn-danger"
                                                                data-toggle="modal" data-target="#removeResModal"
                                                                data-user-id="{{ $row->id }}" >
                                                            @if ($row->extension_status == null )
                                                                Cancel
                                                            @elseif($row->extension_status == "1")
                                                                Remove
                                                            @endif
                                                    </button>
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

    <div class="modal fade" id="removeResModal" data-backdrop="static" tabindex="-1" aria-labelledby="removeResModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeResModalLabel">Remove/Cancel <span class="font-weight-bold username"></span>?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('extension.invite.remove', $extension->id) }}" method="POST" >
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

    @include('extension-programs.extension-services.invite.add', compact('allEmployees', 'extension'))
   
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