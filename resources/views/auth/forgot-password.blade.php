<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>
        <div class="card-body">

            <div class="row mb-3 justify-content-center">
                <div class="col-md-11">
                    <h2 class="h3 font-weight-bold mb-3">Forgot Password</h2>
                    <div class="mb-3">
                        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </div>
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- <x-jet-validation-errors class="mb-3" /> --}}

                    <form method="POST" action="/forgot-password">
                        @csrf

                        <div class="form-group">
                            <x-jet-label value="Email" id="textHome"/>
                            <x-jet-input type="email" name="email" :value="old('email')" class="{{ $errors->has('email') ? 'is-invalid' : '' }} form-control rounded-0 login-field" autofocus />
                            <x-jet-input-error for="email"></x-jet-input-error>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <x-jet-button class="rounded-0">
                                {{ __('Email Password Reset Link') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>