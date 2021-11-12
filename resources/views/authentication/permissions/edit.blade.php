<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Permission') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row justify-content-center">

        <div class="col-md-9 offset-md-2">
          <div class="d-flex align-content-center">
            <h2 class="ml-3 mr-3">Permissions</h2>
            <p class="mt-2 mr-3">Edit Permission.</p>
            <p class="mt-2">
              <a class="back_link" href="{{ route('admin.permissions.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Permissions</a>
            </p>
          </div>

          <div class="col-md-9">
            <div class="card">
              <div class="card-body">
                <form method="POST" action="{{ route('admin.permissions.update', $permission->id) }}">
                  @csrf
                  @method('PUT')
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <x-jet-label value="{{ __('Name') }}" />
                        <x-jet-input class="{{ $errors->has('permission_name') ? 'is-invalid' : '' }}" onfocus="this.selectionStart = this.selectionEnd = this.value.length;"  
                                      autofocus="true" type="text" name="permission_name"
                                    :value="old('permission_name', $permission->name)" required autocomplete="permission_name" />
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