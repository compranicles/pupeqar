<x-app-layout>
<x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit Department') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row">

        <div class="col-md-7 float-none m-0 m-auto">
          <div class="d-flex align-content-center">
            <h2 class="mr-3">Departments</h2>
            <p class="mt-2 mr-3">Edit Department.</p>
            <p class="mt-2">
              <a class="back_link" href="{{ route('admin.departments.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Departments</a>
            </p>
          </div>


            <div class="card">
              <div class="card-body">
                <form method="POST" action="{{ route('admin.departments.update', $department->id) }}">
                  @csrf
                  @method('PUT')
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <x-jet-label value="{{ __('Name') }}" />
                        <x-jet-input class="{{ $errors->has('name') ? 'is-invalid' : '' }}"  
                                    type="text" name="name"
                                    :value="old('name', $department->name)" required autocomplete="name" />
                        <x-jet-input-error for="name"></x-jet-input-error>
                      </div>
                      <div class="form-group">
                        <x-jet-label value="{{ __('College') }}" />
                        <select class="{{ $errors->has('college') ? 'is-invalid': '' }} form-control custom-select" name="college" required>
                          @foreach ($colleges as $college)
                            <option value="{{ $college->id }}" {{ old('college', $college->id) == $collegeOfDept ? 'selected' : '' }}>{{ $college->name }}</option>
                          @endforeach
                        </select>
                        <x-jet-input-error for="college"></x-jet-input-error>
                      </div>
                    </div>
                  </div>  
              </div>
            </div>
            <div class="row">
              <div class="mb-0 mt-3 ml-auto mr-2 p-2">
                <div class="d-flex">
                  <a href="{{ route('admin.departments.index') }}" class="btn btn-light mr-3" tabindex="-1" role="button" aria-disabled="true"><i class="bi bi-x-circle mr-2"></i>Cancel</a>
                  <button type="submit" class="btn btn-success"><i class="bi bi-save mr-2"></i>Save</button>
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