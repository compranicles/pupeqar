<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Research Fields Manager') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <h4>{{ $research_form->label }} Field > {{ $research_field->label }}</h4>
                                <hr>
                            </div>
                            {{-- ADD Fields --}}
                            <div class="col-md-12">
                                <a href="{{ route('research-forms.show', $research_form->id) }}" class="btn btn-secondary">
                                    <i class="fas fa-long-arrow-alt-left"></i>
                                </a>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <form action="{{ route('research-forms.research-fields.update', [$research_form->id, $research_field->id]) }}" id="field_form" class="needs-validation" method="POST">
                                    @csrf
                                    @method('put')
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{-- Name Input --}}
                                            <div class="form-group">
                                                <x-jet-label value="{{ __('Label') }}" />
                            
                                                <input class="form-control" type="text" id="label" name="label" value="{{ $research_field->label }}" required>
                                                        
                                                <div class="invalid-feedback">
                                                    This is required. 
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="col-md-12">
                                            {{-- Field Name Input --}}
                                            <div class="form-group">
                                                <x-jet-label value="{{ __('Column Name') }}" />
                            
                                                <input class="form-control" type="text" id="field_name" name="field_name" value="{{ $research_field->name }}" disabled required>
                                                        
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
                                                    <option value="col-md-12" {{ ($research_field->size === 'col-md-12')? 'selected': '' }}>col-md-12</option>
                                                    <option value="col-md-11" {{ ($research_field->size === 'col-md-11')? 'selected': '' }}>col-md-11</option>
                                                    <option value="col-md-10" {{ ($research_field->size === 'col-md-10')? 'selected': '' }}>col-md-10</option>
                                                    <option value="col-md-9" {{ ($research_field->size === 'col-md-9')? 'selected': '' }}>col-md-9</option>
                                                    <option value="col-md-8" {{ ($research_field->size === 'col-md-8')? 'selected': '' }}>col-md-8</option>
                                                    <option value="col-md-7" {{ ($research_field->size === 'col-md-7')? 'selected': '' }}>col-md-7</option>
                                                    <option value="col-md-6" {{ ($research_field->size === 'col-md-6')? 'selected': '' }}>col-md-6</option>
                                                    <option value="col-md-5" {{ ($research_field->size === 'col-md-5')? 'selected': '' }}>col-md-5</option>
                                                    <option value="col-md-4" {{ ($research_field->size === 'col-md-4')? 'selected': '' }}>col-md-4</option>
                                                    <option value="col-md-3" {{ ($research_field->size === 'col-md-3')? 'selected': '' }}>col-md-3</option>
                                                    <option value="col-md-2" {{ ($research_field->size === 'col-md-2')? 'selected': '' }}>col-md-2</option>
                                                    <option value="col-md-1" {{ ($research_field->size === 'col-md-1')? 'selected': '' }}>col-md-1</option>
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
                
                                                <select name="field_type" id="field_type" class="form-control custom-select" disabled required>
                                                    <option value="" disabled>Choose...</option>
                                                    @foreach ($fieldtypes as $fieldtype)
                                                    <option value="{{ $fieldtype->id }}" {{ ($research_field->field_type_id === $fieldtype->id)? 'selected': '' }} class="{{ $fieldtype->name }}">{{ $fieldtype->name }}</option>
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
                
                                                <select name="dropdown" id="dropdown" class="form-control custom-select" disabled>
                                                    <option value="" selected disabled>Choose...</option>
                                                    @foreach ($dropdowns as $dropdown)
                                                    <option value="{{ $dropdown->id }}" {{ ($research_field->dropdown_id === $dropdown->id)? 'selected': '' }}>{{ $dropdown->name }}</option>
                                                    @endforeach
                                                </select>
                
                                                <div class="invalid-feedback">
                                                    This is required. 
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="col-md-12" id="placeholderfield">
                                            {{-- Field Placeholder Input --}}
                                            <div class="form-group">
                                                <x-jet-label value="{{ __('Placeholder') }}" />
                            
                                                <input class="form-control" type="text" id="placeholder" value="{{ $research_field->placeholder }}" name="placeholder">
                                                        
                                                <div class="invalid-feedback">
                                                    This is required. 
                                                </div>
                                            </div>
                                        </div>
                
                                        <div class="col-md-12">
                                            {{-- Required --}}
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="checkbox" value="required" {{ ($research_field->required === 1)? 'checked' : '' }} name="required" id="required">
                                                <label class="form-check-label" for="required">
                                                    Required
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="1" name="visibility" {{ ($research_field->visibility === 1)? 'checked' : '' }} id="default" required>
                                                <label class="form-check-label" for="default">
                                                    Default
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="2" name="visibility" {{ ($research_field->visibility === 2)? 'checked' : '' }} id="readonly" required>
                                                <label class="form-check-label" for="readonly">
                                                    Read-Only
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="3" name="visibility" {{ ($research_field->visibility === 3)? 'checked' : '' }} id="disabled" required>
                                                <label class="form-check-label" for="disabled">
                                                    Disabled
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" value="4" name="visibility" {{ ($research_field->visibility === 4)? 'checked' : '' }} id="hidden" required>
                                                <label class="form-check-label" for="hidden">
                                                    Hidden
                                                </label>
                                            </div>
                                            <hr>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-0">
                                                <div class="d-flex justify-content-end align-items-baseline">
                                                    <button type="submit" class="btn btn-primary mb-2 mr-2">SAVE</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
      <script>
          $ (function (){
            $("#placeholderfield").hide();
            $("#dropdown_field").hide();

            $("#field_type").find("option:selected").each(function(){
                    var fieldtype = $(this).attr("class");
                    if(fieldtype === 'dropdown'){
                        $('#dropdown_field').show();
                        $('#placeholderfield').hide();
                        $('#dropdown').attr('required', '');
                    }else if(fieldtype === 'date' || fieldtype === 'date-range'){
                        $('#placeholderfield').hide();
                    } else if(fieldtype === 'text' || fieldtype === 'number' || fieldtype === 'decimal' || fieldtype === 'textarea'){
                        $("#dropdown_field").hide();
                        $('#placeholderfield').show();
                        $("#dropdown").removeAttr('required');
                    }else{
                        $("#dropdown_field").hide();
                        $("#dropdown").removeAttr('required');
                    }
            });
        });
      </script>
    @endpush
</x-app-layout>