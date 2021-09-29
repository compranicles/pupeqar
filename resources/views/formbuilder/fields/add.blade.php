{{-- Add Modal --}}
<div class="modal fade" id="addFieldModal" data-backdrop="static" tabindex="-1" aria-labelledby="addFieldModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addFieldModalLabel">Add Field</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.forms.fields.store', $form_id) }}" id="field_form" class="needs-validation" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            {{-- Name Input --}}
                            <div class="form-group">
                                <x-jet-label value="{{ __('Label') }}" />
            
                                <input class="form-control" type="text" id="label" name="label" required>
                                        
                                <div class="invalid-feedback">
                                    This is required. 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            {{-- Field Name Input --}}
                            <div class="form-group">
                                <x-jet-label value="{{ __('Field Name') }}" />
            
                                <input class="form-control" type="text" id="field_name" name="field_name" readonly required>
                                        
                                <div class="invalid-feedback">
                                    This is required. 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Field Size Input --}}
                            <div class="form-group">
                                <x-jet-label value="{{ __('Field Size') }}" />
        
                                <select name="size" id="size" class="form-control custom-select" required>
                                    <option value="col-md-12" selected>col-md-12</option>
                                    <option value="col-md-11">col-md-11</option>
                                    <option value="col-md-10">col-md-10</option>
                                    <option value="col-md-9">col-md-9</option>
                                    <option value="col-md-8">col-md-8</option>
                                    <option value="col-md-7">col-md-7</option>
                                    <option value="col-md-6">col-md-6</option>
                                    <option value="col-md-5">col-md-5</option>
                                    <option value="col-md-4">col-md-4</option>
                                    <option value="col-md-3">col-md-3</option>
                                    <option value="col-md-2">col-md-2</option>
                                    <option value="col-md-1">col-md-1</option>
                                </select>
        
                                <div class="invalid-feedback">
                                    This is required. 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            {{-- Field Type --}}
                            <div class="form-group">
                                <x-jet-label value="{{ __('Field Type') }}" />

                                <select name="field_type" id="field_type" class="form-control custom-select" required>
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($fieldtypes as $fieldtype)
                                    <option value="{{ $fieldtype->id }}" class="{{ $fieldtype->name }}">{{ $fieldtype->name }}</option>
                                    @endforeach
                                </select>

                                <div class="invalid-feedback">
                                    This is required. 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12" id="dropdown_field">
                            {{-- Dropdown --}}
                            <div class="form-group">
                                <x-jet-label value="{{ __('Dropdown') }}" />

                                <select name="dropdown" id="dropdown" class="form-control custom-select">
                                    <option value="" selected disabled>Choose...</option>
                                    @foreach ($dropdowns as $dropdown)
                                    <option value="{{ $dropdown->id }}">{{ $dropdown->name }}</option>
                                    @endforeach
                                </select>

                                <div class="invalid-feedback">
                                    This is required. 
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            {{-- Required --}}
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="required" name="required" id="required">
                                    <label class="form-check-label" for="required">
                                        Required
                                    </label>
                                  </div>
                            </div>
                        </div>

                        
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary mb-2" data-dismiss="modal">Cancel</button>
                <button type="submit" id="addField" class="btn btn-success mb-2 mr-2">Add</button>
                </form>
            </div>
        </div>
    </div>
</div>