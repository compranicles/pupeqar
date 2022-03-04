<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div class="card-body">

            <div class="row justify-content-center pb-5">
                <div class="col-md-12">
                    <h2 class="h3 font-weight-bold text-center mb-3" id="textHome">Register</h2>

                    <form method="POST" action="{{ route('register.save') }}">
                        @csrf

                        <input type="hidden" name="emp_code" value="{{ $user->EmpCode ?? '' }}" >
                        <input type="hidden" name="emp_id" value="{{ $user->EmpNo ?? '' }}" >

                        <div class="form-group">
                            <x-jet-label value="{{ __('First Name') }}" id="textHome"/>
        
                            <x-jet-input class="{{ $errors->has('first_name') ? 'is-invalid' : '' }} form-control-lg rounded-0" id="textHome" type="text" name="first_name"
                                         :value="old('first_name', $user->FName)" autofocus autocomplete="first_name" readonly/>
                            <x-jet-input-error for="first_name"></x-jet-input-error>
                        </div>
        
                        <div class="form-group">
                            <x-jet-label value="{{ __('Middle Name') }}" id="textHome"/>
        
                            <x-jet-input class="{{ $errors->has('middle_name') ? 'is-invalid' : '' }} form-control-lg rounded-0" id="textHome" type="text" name="middle_name"
                                         :value="old('middle_name', $user->MName)" autofocus autocomplete="middle_name" readonly/>
                            <x-jet-input-error for="middle_name"></x-jet-input-error>
                        </div>
        
                        <div class="form-group">
                            <x-jet-label value="{{ __('Last Name') }}" id="textHome"/>
        
                            <x-jet-input class="{{ $errors->has('last_name') ? 'is-invalid' : '' }} form-control-lg rounded-0"  id="textHome" type="text" name="last_name"
                                         :value="old('last_name', $user->LName)" autofocus autocomplete="last_name" readonly/>
                            <x-jet-input-error for="last_name"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <x-jet-label value="{{ __('Email') }}" id="textHome"/>
        
                            <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }} form-control-lg rounded-0" id="textHome" type="email" name="email"
                                         value="{{ old('email', $user->UserName) }}" readonly/>
                            <x-jet-input-error for="email"></x-jet-input-error>
                        </div>
        
                        <div class="form-group">
                            <x-jet-label value="{{ __('Role') }}" id="textHome"/>
        
                            <select name="role" id="role" class="{{ $errors->has('role') ? 'is-invalid' : '' }} form-control form-control-lg rounded-0" id="textHome" required>
                                <option value="" selected disabled>Choose...</option>
                                <option value="1">Faculty</option>
                                <option value="2">Faculty with Designation</option>
                                <option value="3">Admin Employee</option>
                                <option value="4">Admin with Teaching Load</option>
                                <option value="9">None</option>
                            </select>
                            <x-jet-input-error for="role"></x-jet-input-error>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-end align-items-baseline">
                                <x-jet-button id="textHome" class="rounded-0">
                                    {{ __('Register') }}
                                </x-jet-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>