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
                  @method('PUT')
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
                            <option value="" {{ (old('suffix', $user->suffix) == '') ? 'selected' : '' }}>None</option>
                            <option value="Sr" {{ (old('suffix', $user->suffix) == 'Sr') ? 'selected' : '' }}>Sr</option>
                            <option value="Jr" {{ (old('suffix', $user->suffix) == 'Jr') ? 'selected' : '' }}>Jr</option>
                            <option value="III" {{ (old('suffix', $user->suffix) == 'III') ? 'selected' : '' }}>III</option>
                            <option value="IV" {{ (old('suffix', $user->suffix) == 'IV') ? 'selected' : '' }}>IV</option>
                            <option value="V" {{ (old('suffix', $user->suffix) == 'V') ? 'selected' : '' }}>V</option>
                        </select>
                        <x-jet-input-error for="suffix"></x-jet-input-error>
                      </div>  
                      <div class="form-group">
                        <x-jet-label value="{{ __('Email') }}" />
                        <x-jet-input type="email" name="email" :value="old('email', $user->email)" disabled autocomplete="email" />
                      </div>
                      <!-- <div class="form-group">
                <x-jet-label value="{{ __('User Role Permissions') }}" /> -->
                      <div class="form-group">
                        <div class="row">
                          <div class="col-sm-12">
                            <label>Roles</label>
                          </div>
                        </div>
                        <div class="row">
                        @foreach ($roles as $role)
                          
                          <div class="col-sm-6">
                            <label for="role-{{ $role->id }}">
                              <input type="checkbox" class="role-checkbox" id="role-{{ $role->id }}" data-id="{{ $role->id }}" value="{{ $role->id }}" name="roles[]" @if (in_array($role->id, $yourroles)) checked @endif >
                              {{ $role->name }}
                            </label>
                          </div>
                        @endforeach
                        </div>
                      </div>
        
                      <div class="form-group department-input" style="@if($chairperson == null) display: none; @endif">
                        <x-jet-label value="{{ __('Department') }}" />
                        <select name="department" id="department" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ (old('department', $chairperson) == $department->id) ? 'selected' : '' }}>{{ $department->college_name.' - '.$department->name }}</option>  
                            @endforeach
                        </select>
                        <x-jet-input-error for="department"></x-jet-input-error>
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
      <script src="{{ asset('dist/selectize.min.js') }}"></script>
      <script>
          $("#department").selectize({
              sortField: "text",
          });

          $('.role-checkbox').on('change', function() {
              var id = $(this).data('id');
              if(id == 5){
                changeDeptDisp(id);
              }
          });

          function changeDeptDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.department-input').show();
                 $('#department').removeAttr('disabled');
                 $('#department').attr('required', true);
              }
              else{
                $('.department-input').hide();
                $('#department').removeAttr('required');
                $('#department').attr('disabled', true);
              }
          }
      </script>
      <script>
        window.setTimeout(function() {
            $(".alert").fadeTo(500, 0).slideUp(500, function(){
                $(this).remove(); 
            });
        }, 4000);
      </script>
    @endpush
  </x-app-layout>