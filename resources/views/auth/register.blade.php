<x-guest-layout>
    <x-jet-authentication-card>
        <div class="card-body">
            <div class="row mt-1 mb-3">
                <div class="col-md-12">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <div class="d-flex flex-row">
                            <img src="{{ asset('img/logo.png') }}" alt="PUPTlogo" width="50" class="mr-2">
                            <h3 style="color:maroon" class="mt-2" id="textHome">PUPT-QARS</h3>
                        </div>
                    </a>
                    <hr>
                </div>
            </div>
            <div class="row justify-content-center pb-5">
                <div class="col-md-8">
                    <h2 class="h3 font-weight-bold text-center mb-3" id="textHome">Register</h2>

                    <form method="POST" action="{{ route('accept') }}">
                        @csrf
                        <div class="form-group">
                            <x-jet-label value="{{ __('First Name') }}" id="textHome"/>
        
                            <x-jet-input class="{{ $errors->has('first_name') ? 'is-invalid' : '' }} form-control-lg rounded-0" id="textHome" type="text" name="first_name"
                                         :value="old('first_name')" autofocus autocomplete="first_name" />
                            <x-jet-input-error for="first_name"></x-jet-input-error>
                        </div>
        
                        <div class="form-group">
                            <x-jet-label value="{{ __('Middle Name') }}" id="textHome"/>
        
                            <x-jet-input class="{{ $errors->has('middle_name') ? 'is-invalid' : '' }} form-control-lg rounded-0" id="textHome" type="text" name="middle_name"
                                         :value="old('middle_name')" autofocus autocomplete="middle_name" />
                            <x-jet-input-error for="middle_name"></x-jet-input-error>
                        </div>
        
                        <div class="form-group">
                            <x-jet-label value="{{ __('Last Name') }}" id="textHome"/>
        
                            <x-jet-input class="{{ $errors->has('last_name') ? 'is-invalid' : '' }} form-control-lg rounded-0"  id="textHome" type="text" name="last_name"
                                         :value="old('last_name')" autofocus autocomplete="last_name" />
                            <x-jet-input-error for="last_name"></x-jet-input-error>
                        </div>
        
                        <div class="form-group">
                            <x-jet-label value="{{ __('Suffix') }}" id="textHome"/>
        
                            <select name="suffix" id="suffix" class="{{ $errors->has('suffix') ? 'is-invalid' : '' }} form-control form-control-lg rounded-0" id="textHome">
                                <option value="">None</option>
                                <option value="Sr">Sr</option>
                                <option value="Jr">Jr</option>
                                <option value="III">III</option>
                                <option value="IV">IV</option>
                                <option value="V">V</option>
                            </select>
                            <x-jet-input-error for="suffix"></x-jet-input-error>
                        </div>
        
                        <div class="form-group">
                            <x-jet-label value="{{ __('Birth Date') }}" id="textHome"/>
        
                            <x-jet-input class="{{ $errors->has('date_of_birth') ? 'is-invalid' : '' }} form-control-lg rounded-0" id="textHome" type="date" name="date_of_birth"
                                         :value="old('date_of_birth')" autofocus autocomplete="date_of_birth" />
                            <x-jet-input-error for="date_of_birth"></x-jet-input-error>
                        </div>
        
                        <div class="form-group">
                            <x-jet-label value="{{ __('Email') }}" id="textHome"/>
        
                            <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }} form-control-lg rounded-0" id="textHome" type="email" name="email"
                                         value="{{ $invite->email }}" readonly/>
                            <x-jet-input-error for="email"></x-jet-input-error>
                        </div>
        
                        <div class="form-group">
                            <x-jet-label value="{{ __('Password') }}" id="textHome"/>
        
                            <x-jet-input class="{{ $errors->has('password') ? 'is-invalid' : '' }} form-control-lg rounded-0" id="textHome" type="password"
                                         name="password" autocomplete="new-password" />
                            <x-jet-input-error for="password"></x-jet-input-error>
                        </div>
        
                        <div class="form-group">
                            <x-jet-label value="{{ __('Confirm Password') }}" id="textHome"/>
        
                            <x-jet-input class="form-control form-control-lg rounded-0" type="password" name="password_confirmation" autocomplete="new-password" id="textHome"/>
                        </div>
                        <input type="hidden" name="token" value="{{ $invite->token }}"/>
                        @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <x-jet-checkbox id="terms" name="terms" />
                                    <label class="custom-control-label" for="terms">
                                        {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                                    'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'">'.__('Terms of Service').'</a>',
                                                    'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'">'.__('Privacy Policy').'</a>',
                                            ]) !!}
                                    </label>
                                </div>
                            </div>
                        @endif
        
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