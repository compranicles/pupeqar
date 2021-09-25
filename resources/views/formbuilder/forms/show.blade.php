<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __($form->name) }}
        </h2>
    </x-slot>
     

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                {{-- Tab panel --}}
                                <nav>
                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                        <a class="nav-link active" id="nav-form-tab" data-toggle="tab" href="#nav-form" role="tab" aria-controls="nav-form" aria-selected="false">
                                            Form Details
                                        </a>
                                        <a class="nav-link" id="nav-fields-tab" data-toggle="tab" href="#nav-fields" role="tab" aria-controls="nav-fields" aria-selected="true">
                                            Fields
                                        </a>
                                    </div>
                                </nav>
                                <div class="tab-content tab-min-size" id="nav-tabContent">
                                    {{-- Form Details Tab Pane --}}
                                    <div class="tab-pane fade show active" id="nav-form" role="tabpanel" aria-labelledby="nav-form-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="m-3">
                                                    <h4>Form Details</h4>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mx-3">
                                                    <div id="form_message"></div>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="mx-3 mb-3">
                                                    {{-- View/Edit Form Details --}}
                                                    <form id="form_form" data-route="{{ route('admin.forms.update', $form->id) }}" class="needs-validation" method="post" novalidate>
                                                        @csrf
                                                        @method('put')

                                                        {{-- Name Input --}}
                                                        <div class="form-group">
                                                            <x-jet-label value="{{ __('Name') }}" />
                                        
                                                            <input class="form-control" value="{{ $form->name }}" type="text" id="name" name="name" required>
                                                                    
                                                            <div class="invalid-feedback">
                                                                This is required. 
                                                            </div>
                                                        </div>

                                                        {{-- Form Name Input --}}
                                                        <div class="form-group">
                                                            <x-jet-label value="{{ __('Form Name') }}" />
                                        
                                                            <input class="form-control" value="{{ $form->form_name }}" type="text" id="form_name" name="form_name" readonly required>
                                                                    
                                                            <div class="invalid-feedback">
                                                                This is required. 
                                                            </div>
                                                        </div>

                                                        {{-- JavaScript Input --}}
                                                        <div class="form-group">
                                                            <x-jet-label value="{{ __('Javascript (JS)') }}" />
                                        
                                                            <textarea name="javascript" id="javascript" :value="old('javascript')" cols="30" rows="10" 
                                                                    class="{{ $errors->has('message') ? 'is-invalid' : '' }} form-control" 
                                                                    autocomplete="javascript">{{ $form->javascript }}</textarea>
    
                                                            <x-jet-input-error for="javascript"></x-jet-input-error>
                                                        </div>
    
                                                        <div class="mb-0">
                                                            <div class="d-flex justify-content-end align-items-baseline">
                                                                <x-jet-button>
                                                                    {{ __('Save') }}
                                                                </x-jet-button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="m-3">
                                                    <hr>
                                                    <h4>Delete Form</h4>
                                                    {{-- Delete Form Button --}}
                                                    <div class="mb-0">
                                                        <div class="d-flex justify-content-end align-items-baseline">
                                                            <button class="btn btn-danger deletebutton" data-toggle="modal" 
                                                                data-target="#deleteModal" 
                                                                data-id="{{ $form->id }}"
                                                                data-formname="{{ $form->name }}">Delete</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Field Tab Pane --}}
                                    <div class="tab-pane fade" id="nav-fields" role="tabpanel" aria-labelledby="nav-fields-tab">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="m-3">
                                                    <h4>Fields</h4>
                                                </div>
                                            </div>
                                            <hr>
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
    
    {{-- Delete Modal --}}
    @include('formbuilder.forms.delete')

    @push('scripts')
        <script src="{{ asset('js/form.js') }}"></script>
    @endpush
</x-app-layout>