<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Update Event Type') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.event_types.update', $eventType->id) }}">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Event Type Name') }}" />
                    
                                        <x-jet-input class="{{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                                                    :value="old('name', $eventType->name )" required autofocus autocomplete="name" />
                                        <x-jet-input-error for="first_name"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Description') }}" />
                    
                                        <x-jet-input class="{{ $errors->has('description') ? 'is-invalid' : '' }}" type="text" name="description"
                                                    :value="old('description', $eventType->description )" required autofocus autocomplete="middle_name" />
                                        <x-jet-input-error for="description"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-end align-items-baseline">
                                    <x-jet-button>
                                        {{ __('save') }}
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