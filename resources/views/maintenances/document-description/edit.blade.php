<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Description of Documents in '.$report_category->name)}}
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
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="row justify-content-center">
                            <div class="col-md-12">
                                <form action="{{ route('document-description.update', $document_description->id) }}" id="description_form" class="needs-validation" method="POST">
                                    @csrf
                                    @method('put')
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{-- Name Input --}}
                                            <div class="form-group">
                                                <x-jet-label value="{{ __('Name') }}" />
                            
                                                <input class="form-control" type="text" id="name" name="name" value="{{ $document_description->name }}" required>
                                                        
                                                <div class="invalid-feedback">
                                                    This is required. 
                                                </div>
                                            </div>
                                        </div>
                                    </div>     
                                    <div class="row">
                                        <div class="col-md-12">
                                            {{-- Is Active Input --}}
                                            <div class="form-group">
                                                <x-jet-label value="{{ __('Active') }}" />
                                                <div class="custom-control custom-switch">

                                                    <input type="checkbox" class="custom-control-input active-switch" name="is_active" id="is_active_{{ $document_description->id }}" data-id="{{ $document_description->id }}" {{ ($document_description->is_active == 1) ? 'checked': '' }}>
                                                    <label class="custom-control-label" for="is_active_{{ $document_description->id }}"></label>
                                                            
                                                    <div class="invalid-feedback">
                                                        This is required. 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-0">
                                                <div class="d-flex justify-content-end align-items-baseline">
                                                    <a href="{{ url()->previous() }}" class="btn btn-secondary mr-3">Cancel</a>
                                                    <button type="submit" class="btn btn-primary mb-2 mr-2">Save</button>
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
</x-app-layout>