<x-app-layout>
    <x-slot name="header">
            <h2 class="h4 font-weight-bold">
                {{ __('Edit Currency') }}
            </h2>
        </x-slot>
        <div class="container">
          <div class="row">
            <div class="col-md-12">
              @include('maintenances.navigation-bar')
            </div>
    
    
            <div class="col-md-7 float-none m-0 m-auto">
              <h2 class="font-weight-bold mb-2">Currencies</h2>
              <div class="d-flex align-content-center">
                <p>
                  <a class="back_link" href="{{ route('currencies.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Currencies</a>
                </p>
              </div>
    
    
                <div class="card">
                  <div class="card-body">
                    <form method="POST" action="{{ route('currencies.update', $currency->id) }}">
                      @csrf
                      @method('PUT')
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <x-jet-label value="{{ __('Name') }}" />
                            <x-jet-input class="{{ $errors->has('name') ? 'is-invalid' : '' }}" onfocus="this.selectionStart = this.selectionEnd = this.value.length;"  
                                          autofocus="true" type="text" name="name"
                                        :value="old('name', $currency->name)" required autocomplete="name" />
                            <x-jet-input-error for="name"></x-jet-input-error>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <x-jet-label value="{{ __('Code') }}" />
                            <x-jet-input class="{{ $errors->has('code') ? 'is-invalid' : '' }}" onfocus="this.selectionStart = this.selectionEnd = this.value.length;"  
                                          autofocus="true" type="text" name="code"
                                        :value="old('code', $currency->code)" required autocomplete="code" />
                            <x-jet-input-error for="code"></x-jet-input-error>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <div class="form-group">
                            <x-jet-label value="{{ __('Symbol') }}" />
                            <x-jet-input class="{{ $errors->has('symbol') ? 'is-invalid' : '' }}" onfocus="this.selectionStart = this.selectionEnd = this.value.length;"  
                                          autofocus="true" type="text" name="symbol"
                                        :value="old('symbol', $currency->symbol)" required autocomplete="symbol" />
                            <x-jet-input-error for="symbol"></x-jet-input-error>
                          </div>
                        </div>
                      </div>  
                      <div class="row mt-3">
                        <div class="col-md-12">
                          <div class="mb-0">
                              <div class="d-flex justify-content-end align-items-baseline">
                                <a href="{{ route('currencies.index') }}" class="btn btn-secondary mr-2" tabindex="-1" role="button" aria-disabled="true">Cancel</a>
                                <button type="submit" class="btn btn-success">Save</button>
                              </div>
                          </div>
                        </div>
                      </div>   
                  </div>
                </div>
              </form>
    
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