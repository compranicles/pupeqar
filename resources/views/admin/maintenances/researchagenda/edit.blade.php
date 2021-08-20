<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Maintenances > Research Agendas > Edit') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <div class="mb-3 ml-1">
                            <div class="d-inline mr-2">
                                <a href="{{ route('admin.maintenances.researchagenda') }}" class="btn btn-secondary mb-2"><i class="fas fa-arrow-left mr-2"></i> Back</a>
                            </div>
                        </div>
                        <hr>
                        <form method="POST" action="{{ route('admin.maintenances.researchagenda.update', $research_agenda->id) }}">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Name') }}" />
                    
                                        <x-jet-input class="{{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                                                    :value="old('name', $research_agenda->name)" autofocus autocomplete="name" />
                                        <x-jet-input-error for="name"></x-jet-input-error>
                                    </div>
                                </div>
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
            </div>
        </div>
    </div>
</x-app-layout>