<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Create User') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row justify-content-center">

        
        <div class="col-md-9 offset-md-2">
          <div class="d-flex align-content-center">
            <h2 class="ml-3 mr-3">Users</h2>
            <p class="mt-2 mr-3">Create User.</p>
            <p class="mt-2">
              <a class="back_link" href="{{ route('admin.users.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Users</a>
            </p>
          </div>

          <div class="col-md-9">
            @if ($message = Session::get('add_user_success'))
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
                <form method="POST" action="{{ route('admin.users.store') }}">
                  @csrf
                  <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                          <x-jet-label value="{{ __('First Name') }}" />
                          <x-jet-input class="{{ $errors->has('first_name') ? 'is-invalid' : '' }}" type="text" name="first_name"
                                     required autofocus />
                          <x-jet-input-error for="first_name"></x-jet-input-error>
                        </div>
                        <div class="form-group">
                          <x-jet-label value="{{ __('Middle Name') }}" />
                          <x-jet-input class="{{ $errors->has('middle_name') ? 'is-invalid' : '' }} form-control" type="text" name="middle_name" />
                          <x-jet-input-error for="middle_name"></x-jet-input-error>
                        </div>
                        <div class="form-group">
                          <x-jet-label value="{{ __('Last Name') }}" />
                          <x-jet-input class="{{ $errors->has('last_name') ? 'is-invalid' : '' }}" type="text" name="last_name"
                                      required />
                          <x-jet-input-error for="last_name"></x-jet-input-error>
                        </div>
                        <div class="form-group">
                          <x-jet-label value="{{ __('Suffix') }}" />
                          <select name="suffix" id="suffix" class="{{ $errors->has('suffix') 
                          ? 'is-invalid': '' }} form-control form-control-md">
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
                          <x-jet-input class="{{ $errors->has('email') ? 'is-invalid' : '' }} form-control form-control-md" type="email" name="email" required />
                            <x-jet-input-error for="email"></x-jet-input-error>
                          </div>
                        <div class="form-group">
                          <x-jet-label value="{{ __('Birthdate') }}" />
                          <x-jet-input class="{{ $errors->has('birthdate') ? 'is-invalid' : '' }} form-control form-control-md" type="date" name="birthdate"
                                      required />
                          <x-jet-input-error for="birthdate"></x-jet-input-error>
                        </div>
                        <div class="form-group">
                            <x-jet-label value="{{ __('Password') }}" />
                            <x-jet-input class="{{ $errors->has('password') ? 'is-invalid' : '' }} form-control form-control-md" type="password"
                                        name="password" required />
                            <x-jet-input-error for="password"></x-jet-input-error>
                        </div>
                        <div class="form-group">
                            <x-jet-label value="{{ __('Confirm Password') }}" />
                            <x-jet-input class="form-control form-control-md" type="password" name="password_confirmation" required />
                        </div>
                        <div class="form-group">
                          <!-- <x-jet-label value="{{ __('User Role Permissions') }}" />
                          <div class="form-group"> -->
                            <div class="row">
                              <div class="col-md-12">
                                <label>Roles</label>
                              </div>
                            </div>
                            <div class="row">
                                @foreach ($roles as $role)
                                <div class="col-md-4 ml-3">
                                    <label for="role-{{ $role->id }}">
                                        <input 
                                        class="role-checkbox" type="checkbox" id="role-{{$role->id}}" 
                                        name="roles[]" value="{{ $role->id }}" />
                                        {{ $role->name }}
                                    </label>
                                </div>
                                @endforeach
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