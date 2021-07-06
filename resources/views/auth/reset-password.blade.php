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
                    <h2 class="h3 font-weight-bold text-center mb-3" id="textHome">Reset Password</h2>

                    <form method="POST" action="/reset-password">
                        @csrf

                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="form-group">
                            <x-jet-label value="{{ __('Email') }}" />

                            <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }} form-control-lg rounded-0"  id="textHome" type="email" name="email"
                                        :value="old('email', $request->email)" autofocus />
                            <x-jet-input-error for="email"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <x-jet-label value="{{ __('Password') }}" />

                            <x-jet-input class="{{ $errors->has('password') ? 'is-invalid' : '' }} form-control-lg rounded-0"  id="textHome" type="password"
                                        name="password" autocomplete="new-password" />
                            <x-jet-input-error for="password"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <x-jet-label value="{{ __('Confirm Password') }}" id="textHome"/>

                            <x-jet-input class="{{ $errors->has('password_confirmation') ? 'is-invalid' : '' }} form-control-lg rounded-0"  id="textHome" type="password"
                                        name="password_confirmation" autocomplete="new-password" />
                            <x-jet-input-error for="password_confirmation"></x-jet-input-error>
                        </div>

                        <div class="mb-0">
                            <div class="d-flex justify-content-end" >
                                <x-jet-button  id="textHome" class="rounded-0">
                                    {{ __('Reset Password') }}
                                </x-jet-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>