<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Role') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row justify-content-center">

        <div class="col-md-9 offset-md-2">
          <div class="d-flex align-content-center">
            <h2 class="ml-3 mr-3">Roles</h2>
            <p class="mt-2 mr-3">Edit Role.</p>
            <p class="mt-2">
              <a class="back_link" href="{{ route('admin.roles.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Roles</a>
            </p>
          </div>

          <div class="col-md-9">
            <div class="card">
              <div class="card-body">
                <form method="POST" action="{{ route('admin.roles.update', $role->id) }}">
                  @csrf
                  @method('PUT')
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <x-jet-label value="{{ __('Name') }}" />
                        <x-jet-input class="{{ $errors->has('role_name') ? 'is-invalid' : '' }}"  
                                      autofocus="true" type="text" name="role_name"
                                    :value="old('role_name', $role->name)" required autocomplete="role_name" />
                        <x-jet-input-error for="role_name"></x-jet-input-error>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="row">
                      <div class="col-sm-12">
                        <label>Permissions</label>
                      </div>
                    </div>
                    <div class="row">
                    @forelse ($allpermissions as $permission)
                      <div class="col-md-12 ml-3">
                        <label for="{{ $permission->id }}">
                          <input type="checkbox" id="{{ $permission->id }}" value="{{ $permission->id }}" name="permissions[]" @if (in_array($permission->id, $yourpermissions)) checked @endif />
                          {{ $permission->name }}
                        </label>
                      </div>
                      @empty
                      <div class="m-auto">
                        <p>No permissions found. <a href="">Create now.</a></p>
                      </div>
                    @endforelse
                    </div>
                  </div>
                  <div class="form-group d-flex justify-content-end align-items-baseline">
                    <button type="submit" class="btn btn-success mr-3"><i class="bi bi-save mr-2"></i>Save</button>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-light" tabindex="-1" role="button" aria-disabled="true"><i class="bi bi-x-circle mr-2"></i>Cancel</a>
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