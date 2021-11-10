<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit User') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row justify-content-center">

        
        <div class="col-md-9 offset-md-2">
          <div class="d-flex align-content-center">
            <h2 class="ml-3 mr-3">Users</h2>
            <p class="mt-2 mr-3">Edit User.</p>
            <p class="mt-2">
              <a class="back_link" href="{{ route('admin.users.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Users</a>
            </p>
          </div>

          <div class="col-md-9">
            <div class="card">
              <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                  @csrf
                  <div class="row">
                  <div class="col-md-12">
                      <div class="form-group">
                        <x-jet-label value="{{ __('First Name') }}" />
                        <x-jet-input disabled type="text" name="first_name" :value="old('first_name', $user->first_name)" autocomplete="first_name" />
                        <x-jet-input-error for="first_name"></x-jet-input-error>
                      </div>
                      <div class="form-group">
                        <x-jet-label value="{{ __('Last Name') }}" />
                        <x-jet-input disabled type="text" name="last_name" :value="old('last_name', $user->last_name)" autocomplete="last_name" />
                        <x-jet-input-error for="last_name"></x-jet-input-error>
                      </div>
                      <div class="form-group">
                        <x-jet-label value="{{ __('Suffix') }}" />
                        <select name="suffix" id="suffix" class="form-control form-control-md" disabled>
                            <option value="">None</option>
                            <option value="Sr">Sr</option>
                            <option value="Jr">Jr</option>
                            <option value="III">III</option>
                            <option value="IV">IV</option>
                            <option value="V">V</option>
                        </select>
                        <x-jet-input-error for="suffix"></x-jet-input-error>
                      </div>  
                      <div class="form-group">
                        <x-jet-label value="{{ __('Email') }}" />
                        <x-jet-input type="email" name="email" :value="old('email', $user->email)" disabled autocomplete="email" />
                      </div>
                      <div class="form-group">
                <x-jet-label value="{{ __('User Role Permissions') }}" />
                <div class="form-group ml-3">
                  <div class="row">
                    <div class="col-sm-12">
                      <label>Roles</label>
                    </div>
                  </div>
                  <div class="row">
                  @foreach ($roles as $role)
                  @foreach ($rolehaspermissions as $rolehaspermission)
                    <div class="col-sm-6">
                      <label for="{{ $role->id }}">
                        <input type="checkbox" id="{{ $role->id }}" value="{{ $role->id }}" name="roles[]" {{ $rolehaspermission->role_id == $role->id  ? 'checked' : '' }}>
                        {{ $role->name }}
                      </label>
                    </div>
                    @endforeach
                  @endforeach
                  </div>
                </div>
                <div class="form-group ml-3">
                  <div class="row">
                    <div class="col-sm-12">
                      <label>Permissions</label>
                    </div>
                  </div>
                  <div class="row">
                  @foreach ($permissions as $permission)
                    <div class="col-sm-6">
                      <label for="{{ $permission->id }}">
                        <input type="checkbox" id="{{ $permission->id }}" value="{{ $permission->id }}" name="permissions[]">
                        {{ $permission->name }}
                      </label>
                    </div>
                  @endforeach
                  </div>
                </div>
              </div>       
                    </div>
                  </div>
                </div>
              </div>
            <div class="row">
              <div class="mb-0 mt-3 ml-3">
                <div class="d-flex justify-content-start align-items-baseline">
                  <button type="submit" class="btn btn-success mr-3"><i class="bi bi-save mr-2"></i>Save</button>
                  <a href="{{ route('admin.users.index') }}" class="btn btn-light" tabindex="-1" role="button" aria-disabled="true"><i class="bi bi-x-circle mr-2"></i>Cancel</a>
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