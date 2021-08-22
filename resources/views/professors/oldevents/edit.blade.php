<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Events Attended') }}
        </h2>
    </x-slot>
  
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('professor.events.update', $event->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Event Name') }}" />
        
                                        <x-jet-input :value="old('event_name', $event->name)" class="{{ $errors->has('event_name') ? 'is-invalid' : '' }}" type="text" name="event_name" autofocus  autocomplete="event_name" />
        
                                        <x-jet-input-error for="event_name"></x-jet-input-error>
                                    </div>
                                </div>
                              
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Event Type') }}" />
                                        <select name="event_type" id="event_type" class="form-control {{ $errors->has('event_type') ? 'is-invalid' : '' }}" autofocus autocomplete="event_type">
                                        <option value="" selected disabled>Select Event Type</option>
                                        @foreach($event_types as $event_type)   
                                          <option value="{{ old('event_type', $event->event_type_id) }}" 
                                            {{ (old('event_type', $event->event_type_id) == old('event_type', $event_type->id) ? 'selected' : '' )}}>
                                            {{ $event_type->name}}
                                          </option>    
                                          @endforeach
                                        </select>
                                    </div>
                                   
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Description') }}" />
          
                                        <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="" cols="30" rows="5" autocomplete="description">{{ old('description', $event->description) }}</textarea>
          
                                        <x-jet-input-error for="description"></x-jet-input-error>
                                    </div>  
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Organizer') }}" />
          
                                        <textarea class="form-control {{ $errors->has('organizer') ? 'is-invalid' : '' }}" name="organizer" id="" cols="30" rows="5" autocomplete="organizer"> {{ old('organizer', $event->organizer) }}</textarea>
          
                                        <x-jet-input-error for="organizer"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Sponsor') }}" />
          
                                        <textarea class="form-control {{ $errors->has('sponsor') ? 'is-invalid' : '' }}" name="sponsor" id="" cols="30" rows="5" autocomplete="sponsor">{{ old('sponsor', $event->sponsor) }}</textarea>
          
                                        <x-jet-input-error for="sponsor"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Date Started') }}" />
          
                                        <x-jet-input :value="old('date_started', $event->start_date)" class="{{ $errors->has('date_started') ? 'is-invalid' : '' }}" type="text" id="date-start" name="date_started" autofocus autocomplete="date_started" />
          
                                        <x-jet-input-error for="date_started"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Date Ended') }}" />
          
                                        <x-jet-input :value="old('date_ended', $event->end_date)" class="{{ $errors->has('date_ended') ? 'is-invalid' : '' }}" type="text" id="date-end" name="date_ended" autofocus autocomplete="date_ended" />
          
                                        <x-jet-input-error for="date_ended"></x-jet-input-error>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <x-jet-label value="{{ __('Venue') }}" />
          
                                        <x-jet-input :value="old('location', $event->location)" class="{{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" autofocus autocomplete="location" />
          
                                        <x-jet-input-error for="location"></x-jet-input-error>
                                    </div>
                                </div>
                            </div>  
                            <div class="mb-0">
                                <div class="d-flex justify-content-end align-items-baseline">
                                  <a href="{{ url()->previous()}}" class="btn btn-outline-danger mr-2">
                                    CANCEL
                                  </a>
                                  <x-jet-button>
                                      {{ __('SAVE') }}
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
    <script src="{{ asset('js/litepicker.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/plugins/mobilefriendly.js"></script>
    <script>

        const today = new Date();
        

        const picker = new Litepicker ({
            element: document.getElementById('date-start'),
            elementEnd: document.getElementById('date-end'),
            singleMode: false,
            allowRepick: true,
            resetButton: true,
            numberOfColumns: 2,
            numberOfMonths: 2,
            dropdowns: {
                "minYear":2020,
                "maxYear":null,
                "months":true,
                "years":true,
            },
            firstDay : 0,
            plugins: ['mobilefriendly'],
            mobilefriendly: {
              breakpoint: 480,
            },
        });
    </script>
    @endpush
  </x-app-layout>