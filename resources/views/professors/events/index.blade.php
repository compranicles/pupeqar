<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Events') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($message = Session::get('success_event'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <form action="{{ route('professor.events.search') }}" id="search" method="GET">
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-8 mb-3">
                                                <div class="d-flex flex-column">
                                                    <x-jet-input class="{{ $errors->has('search') ? 'is-invalid' : '' }} form-control-lg" placeholder="Search event name ..."  value="{{ (isset($search) ? $search : '' ) }}" type="text" name="search" autofocus autocomplete="search" />
                                                </div>
                                            </div>
                                            <div class="col-lg-2 mb-3">
                                                <div class="d-flex flex-column">
                                                    <x-jet-button class="btn-lg"><i class="fas fa-search"></i> {{ __('Search') }}</x-jet-button>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-2 ">
                                                <div class="d-flex flex-column text-center">
                                                    <a href="{{ route('professor.events.create') }}" class="btn btn-success btn-lg"><i class="fas fa-plus"></i> Add Event</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-12">
                                <h4 class="font-weight-bold" id="textHome" style="color:maroon">{{ $eventview }}</h4>
                            </div>
                        </div>
                        <div class="row">
                            @if(count($events) > 0)
                                @foreach ($events as $event)
                                <div class="col-lg-6 col-md-12">
                                    <div class="card border border-maroon rounded-left mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-12">
                                                    <h5>
                                                        <a href="{{ route('professor.events.submissions.index', $event->id) }}" class="text-dark"><strong>{{ $event->name }}</strong></a>
                                                    </h5>
                                                    
                                                    <p class="mb-1">
                                                        {{  date('F j, Y', strtotime($event->start_date)) }} - {{ date('F j, Y', strtotime($event->end_date)) }} <br>
                                                        @foreach ($event_types as $event_type)
                                                            {{ ($event->event_type_id == $event_type->id) ? $event_type->name : '' }} 
                                                        @endforeach</td>
                                                    </p>
                                                    <p class="mb-1">
                                                        <i>Created by: {{ $event->first_name." ".$event->last_name }}</i>
                                                    </p>
                                                    <p class="mb-2">
                                                        @if ($event->status == 0)
                                                            <a class="btn btn-warning btn-sm btn-disabled text-dark">Not Reviewed</a>
                                                        @elseif($event->status == 1)
                                                            <a class="btn btn-sm btn-success btn-disabled">Accepted</a>
                                                        @elseif($event->status == 2)
                                                            <a class="btn btn-sm btn-danger btn-disabled">Rejected</a>
                                                        @elseif($event->status == 3)
                                                            <a class="btn btn-sm btn-dark btn-disabled">Closed</a>
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="col-md-12">
                                    <div class="card border border-maroon rounded-left">
                                        <div class="card-body">
                                            <div class="row text-center">
                                                <div class="col-lg-12">
                                                    <h3>Seems Empty...</h3>
                                                    <h5>Add New Event <a href="{{ route('professor.events.create') }}" style="color:maroon">here...</a></h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
     @push('scripts')
        <script>
            window.setTimeout(function() {
                $(".alert").fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove(); 
                });
            }, 4000);
        </script>
    @endpush
</x-app-layout>