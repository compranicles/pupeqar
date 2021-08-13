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
                                                    <x-jet-button class="btn-lg">{{ __('Search') }}</x-jet-button>
                                                </div>
                                            </div>
                                            
                                            <div class="col-lg-2 ">
                                                <div class="d-flex flex-column text-center">
                                                    <a href="{{ route('professor.events.create') }}" class="btn btn-outline-dark btn-lg">Add Event</a>
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
                                <div class="col-md-6">
                                    <div class="card border border-maroon rounded-left mb-3">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-lg-8">
                                                    <h5>
                                                        <a href="" class="text-dark">{{ $event->name }}</a>
                                                    </h5>
                                                    
                                                    <p class="mb-n2">
                                                        {{  date('F j, Y', strtotime($event->start_date)) }} - {{ date('F j, Y', strtotime($event->end_date)) }} <br>
                                                        @foreach ($event_types as $event_type)
                                                            {{ ($event->event_type_id == $event_type->id) ? $event_type->name : '' }} 
                                                        @endforeach</td>
                                                    </p>
                                                </div>
                                                <div class="col-lg-4 mt-3">
                                                        <div class="btn-group" role="group">
                                                            @if ($event->status == 0)
                                                                <a class="btn btn-warning btn-sm btn-disabled text-dark">Pending</a>
                                                            @elseif($event->status == 1)
                                                                <a class="btn btn-sm btn-success btn-disabled text-dark">Accepted</a>
                                                            @elseif($event->status == 2)
                                                                <a class="btn btn-sm btn-danger btn-disabled">Rejected</a>
                                                            @elseif($event->status == 3)
                                                                <a class="btn btn-sm btn-dark btn-disabled">Closed</a>
                                                            @endif
                                                            <a href="{{ route('professor.events.edit', $event->id) }}"  class="btn btn-primary btn-sm">Edit</a>
                                                            <button id="deletebutton" class="btn btn-danger btn-sm">Delete</button>
                                                        </div>
                                                        <form id="destroy-event" action="{{ route('professor.events.destroy', $event->id) }}" method="POST" style="display: none;">
                                                            @method('DELETE')
                                                            @csrf
                                                        </form>
                                                    </form>
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
            document.getElementById('deletebutton').addEventListener('click', function(){
                if(confirm('Are you sure?')){
                    document.getElementById('destroy-event').submit();
                } else {

                }
            });
        </script>
         
     @endpush
</x-app-layout>