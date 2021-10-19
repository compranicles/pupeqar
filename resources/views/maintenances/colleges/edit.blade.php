<x-app-layout>
<x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Edit College') }}
        </h2>
    </x-slot>
    <div class="container">
      <div class="row">

        <div class="col-md-7 float-none m-0 m-auto">
          <div class="d-flex align-content-center">
            <h2 class="mr-3">Colleges</h2>
            <p class="mt-2 mr-3">Edit College.</p>
            <p class="mt-2">
              <a class="back_link" href="{{ route('admin.colleges.index') }}"><i class="bi bi-chevron-double-left"></i>Back to all Colleges</a>
            </p>
          </div>


            <div class="card">
              <div class="card-body">
                <form method="POST" action="{{ route('admin.colleges.update', $college->id) }}">
                  @csrf
                  @method('PUT')
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <x-jet-label value="{{ __('Name') }}" />
                        <x-jet-input class="{{ $errors->has('name') ? 'is-invalid' : '' }}" onfocus="this.selectionStart = this.selectionEnd = this.value.length;"  
                                      autofocus="true" type="text" name="name"
                                    :value="old('name', $college->name)" required autocomplete="name" />
                        <x-jet-input-error for="name"></x-jet-input-error>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <label>Departments</label>
                        </div>
                      </div>
                      <div class="row">
                      @foreach ($departments as $department)
                        <div class="col-md-4 ml-3">
                        <label for="{{ $department->id }}">
                          <input type="checkbox" checked disabled>
                          {{ $department->name }}
                        </label>
                        </div>
                      @endforeach
                      </div>
                    </div>
                  </div>  
              </div>
            </div>
            <div class="row">
              <div class="mb-0 mt-3 ml-auto mr-2 p-2">
                <div class="d-flex">
                  <a href="{{ route('admin.colleges.index') }}" class="btn btn-light mr-3" tabindex="-1" role="button" aria-disabled="true"><i class="bi bi-x-circle mr-2"></i>Cancel</a>
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