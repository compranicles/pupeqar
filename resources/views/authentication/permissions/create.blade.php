<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Create Permission') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row justify-content-center">

        <div class="col-md-9 offset-md-2">
          <div class="d-flex align-content-center">
            <h2 class="ml-3 mr-3">Permissions</h2>
            <p class="mt-2 mr-3">Create Permission.</p>
            <p class="mt-2">
              <a class="back_link" href="{{ route('admin.permissions.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Permissions</a>
            </p>
          </div>

          <div class="col-md-9">

            @if ($message = Session::get('add_permission_success'))
              <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                  <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </symbol>
              </svg>
              <div class="alert alert-success d-flex align-items-center" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                <div class="ml-2">
                  {{ $message }}
                </div>
              </div>            
            @endif 

            <div class="card">
              <div class="card-body">
                <form method="POST" action="{{ route('admin.permissions.store') }}">
                  @csrf
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                          <x-jet-label value="{{ __('Name') }}" />
                          <x-jet-input class="{{ $errors->has('permission_name') ? 'is-invalid' : '' }}" type="text" name="permission_name" 
                                      required autofocus />
                          <x-jet-input-error for="permission_name"></x-jet-input-error>
                      </div>
                    </div>
                  </div>
              </div>
            </div>
            <div class="row">
              <div class="mb-0 mt-3 ml-3">
                <div class="d-flex justify-content-start align-items-baseline">
                  <button type="submit" class="btn btn-success mr-3"><i class="bi bi-save mr-2"></i>Save</button>
                  <a href="{{ route('admin.permissions.index') }}" class="btn btn-light" tabindex="-1" role="button" aria-disabled="true"><i class="bi bi-x-circle mr-2"></i>Cancel</a>
                </div>
              </div>
            </div>
          </form>
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
