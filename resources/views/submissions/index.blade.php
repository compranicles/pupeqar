<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Submissions') }}
        </h2>
    </x-slot>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="card">
                <div class="card-body">
                    {{-- Form selector --}}
                    <form action="{{ route('submissions.select') }}" method="POST">
                        @csrf
                        <div class="row mb-n1">
                            <div class="col-lg-9">
                                <div class="d-flex flex-column mt-1 mb-1">
                                    <div class="form-group">
                                        <select name="form" class="form-control-lg custom-select" id="form" required>
                                            <option value="" selected disabled>Select Form To Submit... </option>
                                            @foreach ($forms as $form)
                                            <option value="{{ $form->id }}">{{ $form->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>  
                                </div>
                            </div>
                            <div class="col-lg-3">
                                <div class="d-flex flex-column">
                                    <button class="btn btn-lg btn-success" type="submit"><i class="fas fa-plus mr-2"></i>Create Submission</button>
                                </div>   
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                            @endif
                        </div>
                        
                        <div class="col-md-12">
                            {{-- table display --}}
                            <div class="table-responsive">
                                <table id="submission_table" class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Submission Name</th>
                                            <th>Date Submitted</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($submissions as $submission)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $submission->form_name }}</td>
                                            <td>{{ $submission->created_at }}</td>
                                            <td>
                                                <a href="{{ route('submissions.edit', $submission->id) }}" class="btn btn-warning btn-sm">View/Edit</a>
                                                <button class="btn btn-danger btn-sm deletebutton" data-toggle="modal" 
                                                                                data-target="#deleteModal" 
                                                                                data-id="{{ $submission->id }}"
                                                                                data-name="{{ $submission->form_name }}">Delete</button>
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

{{-- Delete Modal --}}
@include('submissions.delete')

@push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    <script>
        // putting id to form action (delete Modal)
        $(document).on('click', '.deletebutton', function (){
            let currID =  $(this).data('id');
            let url = document.getElementById('submission_delete').action;
            let name = $(this).data('name');
            url = url.replace(':id', currID);
            document.getElementById('submission_delete').action = url;
            document.getElementById('sdisplay').innerHTML = name;
        });

        $(document).ready( function ($){
            $('#submission_table').DataTable();
        })
    </script>
@endpush
</x-app-layout>