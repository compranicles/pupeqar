<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <x-jet-authentication-card-logo />
        </x-slot>

        <div class="card-body">
            <div class="row justify-content-center">
                <div class="col-md-11">
                    <x-jet-validation-errors class="mb-3 rounded" />
                        @if (session('status'))
                            <div class="alert alert-success mb-3 rounded" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-3 rounded" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif

                    <form method="POST" action="{{ route('register.verify') }}">
                        @csrf
                        <div class="form-group">
                            <x-jet-label value="{{ __('Username') }}" />

                            <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }} login-field rounded" type="email"
                                        name="email" :value="old('email')" required autocomplete="off" />
                            <x-jet-input-error for="email"></x-jet-input-error>
                        </div>

                        <div class="form-group">
                            <x-jet-label value="{{ __('Password') }}" />

                            <x-jet-input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }} login-field rounded" type="password"
                                        name="password" required autocomplete="current-password" />
                            <x-jet-input-error for="password"></x-jet-input-error>
                        </div>

                        <div class="mb-4">
                            <div class="mt-4 d-flex justify-content-center align-items-baseline">

                                <x-jet-button class="rounded w-100">
                                    {{ __('Log In') }}
                                </x-jet-button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row text-center justify-content-center">
                <div class="col-md-11">
                    <strong class="text-dark">User's guide on how to use the system.</strong>
                    <div class="d-inline-flex align-items-center">
                        <i class="bi bi-youtube text-danger mr-2" style="font-size: 25px; padding: 0;"></i> <strong><a class="text-dark" href="https://www.youtube.com/channel/UCAxTR9e39SbxRjaLv6Rz_jg/playlists" target="_blank">YouTube</a></strong>
                        <i class="bi bi-file-pdf-fill text-danger ml-4 mr-2" style="font-size: 25px; padding: 0;"></i> <strong><a class="text-dark" href="https://1drv.ms/b/s!AsaLGxa9uAdzap50psIwOaP8Euc" target="_blank">User Guide</a></strong>
                    </div>
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
