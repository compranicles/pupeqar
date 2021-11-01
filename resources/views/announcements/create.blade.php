<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Create Announcement') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row justify-content-center ">

            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>

            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('announcements.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Title') }}" />
                    
                                        <x-jet-input class="{{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title"
                                                    :value="old('title')" required autofocus autocomplete="name" />
                                        <x-jet-input-error for="title"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Subject') }}" />
                    
                                        <x-jet-input class="{{ $errors->has('subject') ? 'is-invalid' : '' }}" type="text" name="subject"
                                                    :value="old('subject')" required autofocus autocomplete="name" />
                                        <x-jet-input-error for="subject"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Message') }}" />
                    
                                        <textarea name="message" id="message" :value="old('message')" cols="30" rows="10" class="{{ $errors->has('message') ? 'is-invalid' : '' }} form-control" required autofocus autocomplete="message"></textarea>
                                        <x-jet-input-error for="message"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Visibility') }}" />
                                        
                                        <select name="status" id="status" class="{{ $errors->has('status') ? 'is-invalid' : '' }} form-control" :value="old('status')" required autofocus>
                                            <option value="1" selected>Show</option>
                                            <option value="2">Hide</option>
                                        </select>
                                        <x-jet-input-error for="message"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-0">
                                <div class="d-flex justify-content-end align-items-baseline">
                                    <x-jet-button>
                                        {{ __('Create') }}
                                    </x-jet-button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/commonmark/0.28.1/commonmark.min.js"></script>
    <script src="{{ asset('dist/markdown-toolbar.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#message').markdownToolbar();
        });
    </script>
    @endpush
</x-app-layout>