<x-guest-layout>
    <x-jet-authentication-card>

        <div class="card-body">

            <div class="row mt-1 mb-5">
                <div class="col-md-12">
                    <a href="{{ route('home') }}" class="text-decoration-none">
                        <div class="d-flex flex-row">
                            <img src="{{ asset('img/logo.png') }}" alt="PUPTlogo" width="50" class="mr-2">
                            <h3 style="color:maroon" class="mt-2" id="textHome">PUPT-FQRS</h3>
                        </div>
                    </a>
                    <hr>
                </div>
            </div>
            <div class="row mt-5 mb-5 justify-content-center">
                <div class="col-md-8">
                    <h2 class="h3 font-weight-bold text-center mb-3" id="textHome">Forgot Password</h2>
                    <div id="textHome" class="mb-3">
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
                            <x-jet-input type="email" name="email" :value="old('email')" class="{{ $errors->has('email') ? 'is-invalid' : '' }} form-control-lg rounded-0" id="textHome" autofocus />
                            <x-jet-input-error for="email"></x-jet-input-error>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <x-jet-button class="rounded-0" id="textHome">
                                {{ __('Email Password Reset Link') }}
                            </x-jet-button>
                        </div>
                    </form>
                </div>
            </div>
            <hr>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>