<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div class="card-body">
            <div class="row mb-3 justify-content-center">
                <div class="col-md-11">
                    <h5>Log In using your User Name and Password</h5>
                </div>
                <div class="col-md-11">

                    <x-jet-validation-errors class="mb-3 rounded-0" />

                        @if (session('status'))
                            <div class="alert alert-success mb-3 rounded-0" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-3 rounded-0" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                    <form method="POST" action="{{ route('register.verify') }}">
                        @csrf
                        <div class="form-group">
                            <x-jet-label value="{{ __('User Name') }}" />

                            <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }} login-field rounded-0" type="email"
                                        name="email" :value="old('email')" required />
                            <x-jet-input-error for="email"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <x-jet-label value="{{ __('Password') }}" />

                            <x-jet-input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} login-field rounded-0" type="password"
                                        name="password" required autocomplete="current-password" />
                            <x-jet-input-error for="password"></x-jet-input-error>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-end align-items-baseline">

                                <x-jet-button class="rounded-0">
                                    {{ __('Log In') }}
                                </x-jet-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-jet-authentication-card>
    <div class="footer">
        <p><p>By using this service, you understood and agree to the PUP Online Services
            <a href="https://www.pup.edu.ph/terms/" style="color: white; text-decoration: underline;"> Terms of Use </a>and
            <a href="https://www.pup.edu.ph/privacy/" style="color: white; text-decoration: underline;"> Privacy Statement </a></p>
    </div>
</x-guest-layout>
