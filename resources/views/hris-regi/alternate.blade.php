<x-guest-layout>
    <form method="POST" action="{{ route('register.alternate.log') }}">
        @csrf
        <div class="form-group">
            <x-jet-label value="{{ __('UAID') }}" />

            <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }} login-field rounded-0" type="text"
                        name="email" :value="old('email')" required autocomplete="off" />
            <x-jet-input-error for="email"></x-jet-input-error>
        </div>
        <div class="mb-0">
            <div class="d-flex justify-content-end align-items-baseline">

                <x-jet-button class="rounded-0">
                    {{ __('Log In') }}
                </x-jet-button>
            </div>
        </div>
    </form>
</x-guest-layout>

