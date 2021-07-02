<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <h2 class="h2 font-weight-bold text-center mb-4">Register</h2>
        </x-slot>

        <x-jet-validation-errors class="mb-3" />

        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <x-jet-label value="{{ __('First Name') }}" />

                    <x-jet-input class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name"
                                 :value="old('first_name')" required autofocus autocomplete="first_name" />
                    <x-jet-input-error for="first_name"></x-jet-input-error>
                </div>

                <div class="form-group">
                    <x-jet-label value="{{ __('Middle Name') }}" />

                    <x-jet-input class="{{ $errors->has('middle_name') ? 'is-invalid' : '' }}" type="text" name="middle_name"
                                 :value="old('middle_name')" required autofocus autocomplete="middle_name" />
                    <x-jet-input-error for="middle_name"></x-jet-input-error>
                </div>

                <div class="form-group">
                    <x-jet-label value="{{ __('Last Name') }}" />

                    <x-jet-input class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name"
                                 :value="old('last_name')" required autofocus autocomplete="last_name" />
                    <x-jet-input-error for="last_name"></x-jet-input-error>
                </div>

                <div class="form-group">
                    <x-jet-label value="{{ __('Suffix') }}" />

                    <select name="suffix" id="suffix" class="{{ $errors->has('suffix') ? 'is-invalid' : '' }} form-control ">
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
                    <x-jet-label value="{{ __('Birth Date') }}" />

                    <x-jet-input class="{{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}" type="date" name="date_of_birth"
                                 :value="old('date_of_birth')" required autofocus autocomplete="date_of_birth" />
                    <x-jet-input-error for="date_of_birth"></x-jet-input-error>
                </div>

                <div class="form-group">
                    <x-jet-label value="{{ __('Email') }}" />

                    <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email"
                                 :value="old('email')" required />
                    <x-jet-input-error for="email"></x-jet-input-error>
                </div>

                <div class="form-group">
                    <x-jet-label value="{{ __('Password') }}" />

                    <x-jet-input class="{{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                                 name="password" required autocomplete="new-password" />
                    <x-jet-input-error for="password"></x-jet-input-error>
                </div>

                <div class="form-group">
                    <x-jet-label value="{{ __('Confirm Password') }}" />

                    <x-jet-input class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
                </div>

                <div class="form-group">
                    <x-jet-label value="{{ __('Role') }}" />

                    <select name="role_id" id="role_id" class="{{ $errors->has('suffix') ? 'is-invalid' : '' }} form-control ">
                        <option value="1">Admin</option>
                        <option value="2">HAP</option>
                        <option value="3">Professor</option>
                    </select>
                    <x-jet-input-error for="role_id"></x-jet-input-error>
                </div>

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
                        <a class="text-muted mr-3 text-decoration-none" href="{{ route('login') }}">
                            {{ __('Already registered?') }}
                        </a>

                        <x-jet-button>
                            {{ __('Register') }}
                        </x-jet-button>
                    </div>
                </div>
            </form>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>