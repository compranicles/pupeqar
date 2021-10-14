<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit User') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center ">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('users.update', $user->id) }}">
                            @csrf
                            @method('put')
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('First Name') }}" />
                    
                                        <x-jet-input class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name"
                                                    :value="old('first_name', $user->first_name )" required autofocus autocomplete="first_name" />
                                        <x-jet-input-error for="first_name"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Middle Name') }}" />
                    
                                        <x-jet-input class="{{ $errors->has('middle_name') ? 'is-invalid' : '' }}" type="text" name="middle_name"
                                                    :value="old('middle_name', $user->middle_name )" required autofocus autocomplete="middle_name" />
                                        <x-jet-input-error for="middle_name"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Last Name') }}" />
                    
                                        <x-jet-input class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name"
                                                    :value="old('last_name', $user->last_name)" required autofocus autocomplete="last_name" />
                                        <x-jet-input-error for="last_name"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Suffix') }}" />
                    
                                        <select name="suffix" id="suffix" class="{{ $errors->has('suffix') ? 'is-invalid' : '' }} form-control ">
                                            <option value="" {{ ( old('suffix', $user->suffix) == "") ? 'selected' : '' }}>None</option>
                                            <option value="Sr" {{ (old('suffix', $user->suffix) == "Sr") ? 'selected' : '' }}>Sr</option>
                                            <option value="Jr" {{ (old('suffix', $user->suffix) == "Jr") ? 'selected' : '' }}>Jr</option>
                                            <option value="III" {{ (old('suffix', $user->suffix) == "III") ? 'selected' : '' }}>III</option>
                                            <option value="IV" {{ (old('suffix', $user->suffix) == "IV") ? 'selected' : '' }}>IV</option>
                                            <option value="V" {{ (old('suffix', $user->suffix) == "V") ? 'selected' : '' }}>V</option>
                                        </select>
                                        <x-jet-input-error for="suffix"></x-jet-input-error>
                                    </div>
                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Birth Date') }}" />
                    
                                        <x-jet-input class="{{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}" type="date" name="date_of_birth"
                                                    :value="old('date_of_birth', $user->date_of_birth)" required autofocus autocomplete="date_of_birth" />
                                        <x-jet-input-error for="date_of_birth"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Email') }}" />
                    
                                        <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email"
                                                    :value="old('email', $user->email)" disabled/>
                                        <x-jet-input-error for="email"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Role') }}" />
                    
                                        <select name="role_id" id="role_id" class="{{ $errors->has('role_id') ? 'is-invalid' : '' }} form-control ">
                                            <option value="1" {{ ( old('role_id', $user->role_id) == "1") ? 'selected' : '' }}>Admin</option>
                                            <option value="2" {{ ( old('role_id', $user->role_id) == "2") ? 'selected' : '' }}>HAP</option>
                                            <option value="3" {{ ( old('role_id', $user->role_id) == "3") ? 'selected' : '' }}>Professor</option>
                                        </select>
                                        <x-jet-input-error for="role_id"></x-jet-input-error>
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