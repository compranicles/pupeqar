<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Forms') }}
        </h2>
    </x-slot>
     
    <div class="container mt-n4">
        
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                {{-- Tab Panel --}}
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                      <a class="nav-link active" id="nav-home-tab" data-toggle="tab" href="#nav-home" role="tab" aria-controls="nav-home" aria-selected="true">Home</a>
                                      <a class="nav-link" id="nav-arrange-tab" data-toggle="tab" href="#nav-arrange" role="tab" aria-controls="nav-arrange" aria-selected="false">Arrange</a>
                                    </div>
                                </nav>
                                <div class="tab-content tab-min-size" id="nav-tabContent">
                                    {{-- Home Tab --}}
                                    <div class="tab-pane fade show active" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="m-3">
                                                    <h4>List of Forms</h4>
                                                </div>
                                            </div>

                                            {{-- ADD Button --}}

                                            <div class="col-md-12">
                                                <div class="mx-3 mb-3">
                                                    <button class="btn btn-success .add-dropdown" data-toggle="modal" data-target="#addModal">
                                                        Add Form
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                {{-- Success Message --}}
                                                @if ($message = Session::get('success'))
                                                <div class="alert alert-success alert-index mx-3">
                                                    {{ $message }}
                                                </div>
                                                @endif
                                            </div>

                                           
                                            <div class="col-md-12">
                                                <div class="mx-3">
                                                    <div class="table-responsive">
                                                        <table id="form_table" class="table">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Name</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($forms as $form)
                                                                <tr>
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $form->name }}</td>
                                                                    <td>
                                                                        <a href="{{ route('admin.forms.show', $form->id) }}" class="btn btn-warning">Manage</a>
                                                                        <button class="btn btn-danger deletebutton" data-toggle="modal" 
                                                                                data-target="#deleteModal" 
                                                                                data-id="{{ $form->id }}"
                                                                                data-formname="{{ $form->name }}">Delete</button>
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
                                    {{-- Arrange Tab --}}
                                    <div class="tab-pane fade" id="nav-arrange" role="tabpanel" aria-labelledby="nav-arrange-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="m-3">
                                                    <h4>Manage Forms</h4>
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="mx-3">
                                                    <div id="alert_message"></div>
                                                </div>
                                            </div>

                                           

                                            {{-- Divide the row to 3 parts for 3 tables --}}
                                            {{-- Forms Table --}}
                                            <div class="col-md-4">
                                                <div class="mx-3 overflow-auto table_container">
                                                    <div class="table-responsive text-center">
                                                        <table id="all_table" class="table table-bordered p-2">
                                                            <tbody class="connectedSortable">
                                                                <tr>
                                                                    <th><h5>Non-Assigned Forms</h5></th>
                                                                </tr>
                                                                @foreach ($notAssignedForms as $form)
                                                                <tr data-id="{{ $form->id }}">
                                                                    <td>{{ $form->name }}</td>
                                                                </tr>                                                                    
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- QAR Forms Table --}}
                                            <div class="col-md-4">
                                                <div class="mx-3  overflow-auto table_container">
                                                    <div class="table-responsive text-center">
                                                        <table id="qar_table" class="table table-bordered">
                                                            <tbody class="connectedSortable">
                                                                <tr>
                                                                    <th><h5>QAR Forms</h5></th>
                                                                </tr>
                                                                @foreach ($forms as $form )
                                                                @if (in_array($form->id, $qarFormsId, true))
                                                                <tr data-id="{{ $form->id }}">
                                                                    <td>{{ $form->name }}</td>
                                                                </tr>    
                                                                @endif
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Non-QAR Forms Table --}}
                                            <div class="col-md-4">
                                                <div class="mx-3  overflow-auto table_container">
                                                    <div class="table-responsive text-center">
                                                        <table id="nonqar_table" class="table table-bordered">
                                                            <tbody class="connectedSortable">
                                                                <tr>
                                                                    <th><h5>Non-QAR Forms</h5></th>
                                                                </tr>
                                                                @foreach ($forms as $form )
                                                                @if (in_array($form->id, $nonQarFormsId, true))
                                                                <tr data-id="{{ $form->id }}">
                                                                    <td>{{ $form->name }}</td>
                                                                </tr>    
                                                                @endif
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                             {{-- Save button --}}
                                            <div class="col-md-12">
                                                <div class="mx-3 mb-3">
                                                    <div class="mb-0">
                                                        <div class="d-flex justify-content-end align-items-baseline">
                                                            <x-jet-button id="save_arrange" data-save="{{ route('admin.forms.arrange') }}" >
                                                                {{ __('Save Arrangement') }}
                                                            </x-jet-button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Add Modal --}}
    @include('formbuilder.forms.add')

    {{-- Delete Modal --}}
    @include('formbuilder.forms.delete')

    @push('scripts')
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
        <script src="{{ asset('jquery-ui/jquery-ui.js') }}"></script>
        <script src="{{ asset('js/form.js') }}"></script>
    @endpush
</x-app-layout>