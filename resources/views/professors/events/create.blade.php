<x-app-layout>
  <x-slot name="header">
    <h2 class="h4 font-weight-bold">
        {{ __('Create Events Attended') }}
    </h2>
  </x-slot>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
          <div class="card">
              <div class="card-body">
                  <form action="{{ route('professor.events.store') }}" method="POST">
                      @csrf
                      <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                              <x-jet-label value="{{ __('Event Type') }}" />

                              <select name="event_type" id="event_type" class="form-control {{ $errors->has('event_type') ? 'is-invalid' : '' }}" required autofocus autocomplete="event_type">
                                <option value="" selected disabled>------------------------ Select Event Type ------------------------</option>
                                @foreach($event_types as $event_type)
                                  <option value="{{ $event_type->id }}">{{ $event_type->name }}</option>    
                              @endforeach
                            </select>
                            </div>
                        </div>
                          <div class="col-lg-12">
                              <div class="form-group">
                                <x-jet-label value="{{ __('Event Name') }}" />

                                <x-jet-input class="{{ $errors->has('event_name') ? 'is-invalid' : '' }}" type="text" name="event_name" required autocomplete="event_name" />

                                <x-jet-input-error for="event_name"></x-jet-input-error>
                              </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="form-group">
                              <x-jet-label value="{{ __('Description') }}" />

                              <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="" cols="30" rows="3" autocomplete="description"></textarea>

                              <x-jet-input-error for="description"></x-jet-input-error>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="form-group">
                              <x-jet-label value="{{ __('Organizer') }}" />

                              <textarea class="form-control {{ $errors->has('organizer') ? 'is-invalid' : '' }}" name="organizer" id="" cols="30" rows="3" autocomplete="organizer"></textarea>

                              <x-jet-input-error for="organizer"></x-jet-input-error>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="form-group">
                              <x-jet-label value="{{ __('Sponsor') }}" />

                              <textarea class="form-control {{ $errors->has('sponsor') ? 'is-invalid' : '' }}" name="sponsor" id="" cols="30" rows="3" autocomplete="sponsor"></textarea>

                              <x-jet-input-error for="sponsor"></x-jet-input-error>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="form-group">
                              <x-jet-label value="{{ __('Date Started') }}" />

                              <x-jet-input class="{{ $errors->has('date_started') ? 'is-invalid' : '' }}" type="date" name="date_started" required autofocus autocomplete="date_started" />

                              <x-jet-input-error for="date_started"></x-jet-input-error>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="form-group">
                              <x-jet-label value="{{ __('Date Ended') }}" />

                              <x-jet-input class="{{ $errors->has('date_ended') ? 'is-invalid' : '' }}" type="date" name="date_ended" required autofocus autocomplete="date_ended" />

                              <x-jet-input-error for="date_started"></x-jet-input-error>
                            </div>
                          </div>
                          <div class="col-lg-12">
                            <div class="form-group">
                              <x-jet-label value="{{ __('Location') }}" />

                              <x-jet-input class="{{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" required autofocus autocomplete="location" />

                              <x-jet-input-error for="location"></x-jet-input-error>
                            </div>
                          </div>
                      </div>
                      <div class="mb-0">
                        <div class="d-flex justify-content-end align-items-baseline">
                            <button type="reset" class="reset btn btn-outline-dark">
                                Reset
                            </button>
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
</x-app-layout>