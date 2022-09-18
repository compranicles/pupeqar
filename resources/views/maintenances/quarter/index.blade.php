<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Quarter and Year') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('maintenances.navigation-bar')
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h2 class="font-weight-bold mb-2">Quarter & Year of Reporting</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @if ($message = Session::get('success'))
                <div class="alert alert-success alert-index">
                    <i class="bi bi-check-circle"></i> {{ $message }}
                </div>         
                @endif
            </div>
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="col-md-12">
                            <form action="{{ route('maintenance.quarter.update') }}" id="field_form" method="POST">
                                @csrf
                                {{-- Quarteer --}}
                                <div class="form-group">
                                    <x-jet-label value="{{ __('Quarter') }}" />

                                    <select name="current_quarter" id="quarter" class="form-control custom-select" required>
                                        <option value="" selected disabled>Choose...</option>
                                        <option value="1" {{ isset($quarter->current_quarter) ? ($quarter->current_quarter == '1' ? 'selected' : '') : '' }}>1</option>
                                        <option value="2" {{ isset($quarter->current_quarter) ? ($quarter->current_quarter == '2' ? 'selected' : '') : '' }}>2</option>
                                        <option value="3" {{ isset($quarter->current_quarter) ? ($quarter->current_quarter == '3' ? 'selected' : '') : '' }}>3</option>
                                        <option value="4" {{ isset($quarter->current_quarter) ? ($quarter->current_quarter == '4' ? 'selected' : '') : '' }}>4</option>
                                    </select>
                                </div>

                                {{-- Year --}}
                                <div class="form-group">
                                    <x-jet-label value="{{ __('Year') }}" />
                
                                    <input type="text" name="current_year" maxlength="4" class="form-control"  min="0" max="9999" step="1" placeholder="YYYY" pattern="[0-9]{4}" value="{{ isset($quarter->current_year) ? $quarter->current_year : '' }}" required/>
                                    
                                </div>

                                <div class="form-group">
                                    <x-jet-label value="{{ __('Deadline') }}" />
                                    <input type="date" name="deadline" class="form-control" value="{{ isset($quarter->deadline) ? $quarter->deadline : '' }}" required>
                                </div>

                                <div class="mb-0">
                                    <div class="d-flex justify-content-end align-items-baseline">
                                        <button type="submit" class="btn btn-primary mb-2 mr">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @push('scripts')
    <script>
        // auto hide alert
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
    </script>
    @endpush

</x-app-layout>