<x-guest-layout>
    <x-jet-authentication-card>
        
        <div class="card-body">

            <div class="row mt-5 mb-3">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center">
                        <img src="{{ asset('img/logo.png') }}" alt="PUPTLogo" width="100">
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="d-flex justify-content-center">
                        <h1 class="text-center" id="pupTextHome">
                            <span id="pupTaguig">PUP-Taguig</span><br><span class="h2">Faculty Quarterly Report System</span>
                        </h1>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">

                    <h2 class="h3 font-weight-bold text-center mt-5 mb-3">Log In</h2>

                    {{-- <x-jet-validation-errors class="mb-3 rounded-0" /> --}}

                    @if (session('status'))
                        <div class="alert alert-success mb-3 rounded-0" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @if ($message = Session::get('success'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                    @endif
                    @if ($message = Session::get('register-error'))
                        <div class="alert alert-danger">
                            {{ $message }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group">
                            <x-jet-label value="{{ __('Email') }}" />

                            <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                                        name="email" :value="old('email')" required />
                            <x-jet-input-error for="email"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <x-jet-label value="{{ __('Password') }}" />

                            <x-jet-input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" type="password"
                                        name="password" required autocomplete="current-password" />
                            <x-jet-input-error for="password"></x-jet-input-error>
                        </div>

                        {{-- <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <x-jet-checkbox id="remember_me" name="remember" />
                                <label class="custom-control-label" for="remember_me">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div> --}}

                        <div class="mb-0">
                            <div class="d-flex justify-content-end align-items-baseline">
                                @if (Route::has('password.request'))
                                    <a class="text-muted mr-3" href="{{ route('password.request') }}">
                                        {{ __('Forgot your password?') }}
                                    </a>
                                @endif

                                <x-jet-button>
                                    {{ __('Log in') }}
                                </x-jet-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </x-jet-authentication-card>
</x-guest-layout>