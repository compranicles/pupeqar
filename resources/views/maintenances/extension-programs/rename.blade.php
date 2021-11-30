<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Extension Program Forms Manager') }}
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
                                <h4>Renaming {{ $extension_program_form->label }}</h4>
                                <hr>
                            </div>
                            <div class="col-md-12">
                               <form action="{{ route('extension-program-forms.update', $extension_program_form->id) }}" method="post">
                                    @csrf
                                    @method('put')
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Form Label') }}" />
            
                                        <input class="form-control" type="text" id="label" name="label" value="{{ old('label', $extension_program_form->label) }}" required>
                                                
                                        <div class="invalid-feedback">
                                            This is required. 
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('extension-program-forms.index') }}" class="btn btn-secondary mb-2">Cancel</a>
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