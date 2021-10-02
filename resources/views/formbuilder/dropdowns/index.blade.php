<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Dropdowns') }}
        </h2>
    </x-slot>
     
    <div class="container mt-n4">
        
        <div class="row mt-3 mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        {{-- ADD Button --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <button class="btn btn-success .add-dropdown" data-toggle="modal" data-target="#addModal">
                                    Add Dropdown
                                </button>
                            </div>
                        </div>

                        {{-- Success Message --}}
                        @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            {{ $message }}
                        </div>
                        @endif
                        
                        {{-- Table for displaying dropdowns --}}
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="dropdown_table" class="table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Dropdown Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($dropdowns as $dropdown)
                                            <tr>
                                                <th>{{ $loop->iteration }}</th>    
                                                <td>{{ $dropdown->name }}</td>
                                                <td>
                                                    <button class="btn btn-primary edit-row" data-toggle="modal" data-target="#editModal" data-id="{{ $dropdown->id }}">Edit</button>
                                                    <button class="btn btn-danger delete-row" data-toggle="modal" data-dropdownname="{{ $dropdown->name }}" data-target="#deleteModal" data-id="{{ $dropdown->id }}">Delete</button>
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
    
    {{-- Add Dropdown Modal --}}
    @include('formbuilder.dropdowns.add')

    {{-- Edit Dropdown Modal --}}
    @include('formbuilder.dropdowns.edit')

    {{-- Delete Dropdown Modal --}}
    @include('formbuilder.dropdowns.delete')

    @push('scripts')
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
        <script src="{{ asset('js/dropdown.js') }}"></script>
    @endpush
</x-app-layout>