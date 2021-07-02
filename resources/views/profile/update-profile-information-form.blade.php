<x-jet-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">

        <x-jet-action-message on="saved">
            {{ __('Saved.') }}
        </x-jet-action-message>

        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div class="form-group" x-data="{photoName: null, photoPreview: null}">
                <!-- Profile Photo File Input -->
                <input type="file" hidden
                       wire:model="photo"
                       x-ref="photo"
                       x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-jet-label for="photo" value="{{ __('Photo') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" class="rounded-circle" height="80px" width="80px">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview">
                    <img x-bind:src="photoPreview" class="rounded-circle" width="80px" height="80px">
                </div>

                <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Select A New Photo') }}
				</x-jet-secondary-button>
				
				@if ($this->user->profile_photo_path)
                    <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Remove Photo') }}
                    </x-jet-secondary-button>
                @endif

                <x-jet-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <div class="w-md-75">
            <!-- Name -->
            <div class="form-group">
                <x-jet-label for="first_name" value="{{ __('First Name') }}" />
                <x-jet-input id="first_name" type="text" class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}" wire:model.defer="state.first_name" autocomplete="first_name" />
                <x-jet-input-error for="first_name" />
            </div>

            <div class="form-group">
                <x-jet-label for="middle_name" value="{{ __('Middle Name') }}" />
                <x-jet-input id="middle_name" type="text" class="{{ $errors->has('middle_name') ? 'is-invalid' : '' }}" wire:model.defer="state.middle_name" autocomplete="middle_name" />
                <x-jet-input-error for="middle_name" />
            </div>

            <div class="form-group">
                <x-jet-label for="last_name" value="{{ __('Last Name') }}" />
                <x-jet-input id="last_name" type="text" class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}" wire:model.defer="state.last_name" autocomplete="last_name" />
                <x-jet-input-error for="last_name" />
            </div>

            <div class="form-group">
                <x-jet-label for="suffix" value="{{ __('Suffix') }}" />
                <select name="suffix" id="suffix" class="{{ $errors->has('suffix') ? 'is-invalid' : '' }} form-control " wire:model.defer="state.suffix">
                    <option value="">None</option>
                    <option value="Sr">Sr</option>
                    <option value="Jr">Jr</option>
                    <option value="III">III</option>
                    <option value="IV">IV</option>
                    <option value="V">V</option>
                </select>
                <x-jet-input-error for="suffix" />
            </div>

            <div class="form-group">
                <x-jet-label value="{{ __('Birth Date') }}" />

                <x-jet-input class="{{ $errors->has('date_of_birth') ? 'is-invalid' : '' }}" type="date" name="date_of_birth"
                                wire:model.defer="state.date_of_birth" autocomplete="date_of_birth" />
                <x-jet-input-error for="date_of_birth"></x-jet-input-error>
            </div>


            <!-- Email -->
            <div class="form-group">
                <x-jet-label for="email" value="{{ __('Email') }}" />
                <x-jet-input id="email" type="email" class="{{ $errors->has('email') ? 'is-invalid' : '' }}" wire:model.defer="state.email" disabled/>
                <x-jet-input-error for="email" />
            </div>
        </div>
    </x-slot>

    <x-slot name="actions">
        
		<div class="d-flex align-items-baseline">
			<x-jet-button>
				{{ __('Save') }}
			</x-jet-button>
		</div>
    </x-slot>
</x-jet-form-section>