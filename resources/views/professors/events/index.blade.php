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
                                <form action="{{ route('professor.events.search') }}" method="GET">
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
                                                    <a href="{{ route('professor.events.create') }}" class="btn btn-outline-dark btn-lg ml-2">Can't find it?</a>
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
                            @foreach ($events as $event)
                            <div class="col-md-6">
                                <div class="card border border-maroon rounded-left mb-3">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-lg-9">
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
                                            <div class="col-lg-3">
                                                <p>
                                                    @if ($event->status == 0)
                                                        <h6 class="btn btn-warning btn-sm btn-disabled text-dark">Pending</h6>
                                                    @elseif($event->status == 1)
                                                        <h6 class="badge badge-success text-wrap m-0">Accepted</h6>
                                                    @elseif($event->status == 2)
                                                        <h6 class="badge badge-danger text-wrap m-0">Rejected</h6>
                                                    @elseif($event->status == 3)
                                                        <h6 class="badge badge-dark text-wrap m-0">Closed</h6>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
</x-app-layout>