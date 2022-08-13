<x-app-layout>
@section('title', 'Academic Module Fields Manager |')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Academic Module Fields Manager</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <h4>Renaming {{ $academic_module_form->label }}</h4>
                                <hr>
                            </div>
                            <div class="col-md-12">
                               <form action="{{ route('academic-module-forms.update', $academic_module_form->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Form Label') }}" />
            
                                        <input class="form-control" type="text" id="label" name="label" value="{{ old('label', $academic_module_form->label) }}" required>
                                                
                                        <div class="invalid-feedback">
                                            This is required. 
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('academic-module-forms.index') }}" class="btn btn-secondary mb-2">Cancel</a>
                                        <button type="submit" class="btn btn-success mb-2 mr-2">Rename</button>
                                        </form>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>