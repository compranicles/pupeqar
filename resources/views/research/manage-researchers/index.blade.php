<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Manage Researchers of '.$research_code) }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <p>
                    <a class="back_link" href="{{ route('research.show', $research->id) }}"><i class="bi bi-chevron-double-left"></i>Back to all Research Registration</a>
                </p>
                <div class="row">
                    <div class="col-md-12">
                        {{-- Success Message --}}
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success alert-index mx-3">
                            {{ $message }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <h4>
                                    Active Researchers
                                    <hr>
                                </h4>
                                <div class="table-responsive">
                                    
                                    <table class="table text-center table-hover table-bordered" id="researchTable" >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Researcher</th>
                                                <th>Nature of Involvement</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($researchers as $research)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $research->last_name.', '.$research->first_name.' '.$research->middle_name }}</td>
                                                    <td>{{ $research->nature_of_involvement_name }}</td>
                                                    <td>
                                                        @if($research->nature_of_involvement != 11)
                                                            <button id="edit_button" class="btn btn-sm btn-warning text-dark" 
                                                                        data-toggle="modal" data-target="#editModal" 
                                                                        data-user-id="{{ $research->user_id }}" 
                                                                        data-nat-involve="{{ $research->nature_of_involvement }}"
                                                                        data-username="{{ $research->last_name.', '.$research->first_name.' '.$research->middle_name }}">
                                                                Edit Role
                                                            </button>
                                                            <button id="remove_button" class="btn btn-sm btn-danger"
                                                                        data-toggle="modal" data-target="#removeModal"
                                                                        data-username="{{ $research->last_name.', '.$research->first_name.' '.$research->middle_name }}"
                                                                        data-user-id="{{ $research->user_id }}" >
                                                                <i class="fas fa-user-times"></i>
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
                
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>
                                    Inactive Researchers
                                    <hr>
                                </h4>
                                <div class="table-responsive">
                                    
                                    <table class="table text-center table-hover table-bordered" id="inactiveTable" >
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Researcher</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($inactive_researchers as $research)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ $research->last_name.', '.$research->first_name.' '.$research->middle_name }}</td>
                                                    <td>
                                                        <button id="return_button" class="btn btn-sm btn-success"
                                                                    data-toggle="modal" data-target="#returnModal"
                                                                    data-username="{{ $research->last_name.', '.$research->first_name.' '.$research->middle_name }}"
                                                                    data-user-id="{{ $research->user_id }}" >
                                                            <i class="fas fa-user-plus"></i>
                                                        </button>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="3" class="text-center">No Record</td>
                                                </tr>
                                            @endforelse
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

    <div class="modal fade" id="editModal" data-backdrop="static" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Role of <span id="user_name" class="font-weight-bold"></span></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <form action="{{ route('research.save-role', $research_code) }}" method="POST" >
                        @csrf

                        <input type="hidden" id="user_id" name="user_id" value="">
    
                        {{-- Options Input --}}
                        <div class="form-group">
                            <x-jet-label value="{{ __('Options') }}" class="mb-n2"/>
                            <select name="nature_of_involvement" id="involvement" class="form-control custom-select">
                                <option selected disabled>Choose...</option>
                                @foreach ($nature_of_involvement_dropdown as $row)
                                    @if ($row->id == 11)
                                        @continue
                                    @endif
                                    <option value="{{ $row->id }}">{{ $row->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success mb-2 mr-2">SAVE</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="removeModal" data-backdrop="static" tabindex="-1" aria-labelledby="removeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeModalLabel">Remove <span class="font-weight-bold username"></span>?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('research.remove-researcher', $research_code) }}" method="POST" >
                    @csrf

                    <input type="hidden" id="remove_id" name="user_id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-danger mb-2 mr-2">YES</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="returnModal" data-backdrop="static" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="returnModalLabel">Make <span class="font-weight-bold username"></span>active?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('research.return-researcher', $research_code) }}" method="POST" >
                    @csrf

                    <input type="hidden" id="return_id" name="user_id" value="">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">No</button>
                    <button type="submit" class="btn btn-success mb-2 mr-2">YES</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@push('scripts')
    <script>
        var id;
        var natId;
        var name; 

        $('#edit_button').on('click', function(){
            id = $(this).data('user-id');
            natId = $(this).data('nat-involve');
            name = $(this).data('username');

            document.getElementById('user_id').value = id;
            $('#user_name').text(name);
            $('#involvement option[value='+natId+']').attr('selected','selected');
        });

        $('#editModal').on('hidden.bs.modal', function (){
            document.getElementById('user_id').value = "";
            $('#user_name').text('');
            $('#involvement option[value='+natId+']').removeAttr('selected');
        });

        $('#remove_button').on('click', function(){
            id = $(this).data('user-id');
            name = $(this).data('username');
            var url = 
            document.getElementById('remove_id').value = id;
            $('.username').text(name);
        });

        $('#removeModal').on('hidden.bs.modal', function (){
            document.getElementById('remove_id').value = "";
            $('.username').text('');
        });

        $('#return_button').on('click', function(){
            id = $(this).data('user-id');
            name = $(this).data('username');
            var url = 
            document.getElementById('return_id').value = id;
            $('.username').text(name);
        });

        $('#returnModal').on('hidden.bs.modal', function (){
            document.getElementById('return_id').value = "";
            $('.username').text('');
        });
    </script>
@endpush
</x-app-layout>
