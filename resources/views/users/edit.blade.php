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
            <p class="mt-2 ml-3">
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
                        <select name="department[]" id="department" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                            {{-- @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ (old('department', $chairperson) == $department->id) ? 'selected' : '' }}>{{ $department->name }}</option>  
                            @endforeach --}}
                        </select>
                        <x-jet-input-error for="department"></x-jet-input-error>
                      </div>  

                      <div class="form-group college-input" style="@if($dean == null) display: none; @endif">
                        <x-jet-label value="{{ __('Office/College/Branch/Campus') }}" />
                        <select name="college[]" id="college" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                            {{-- @foreach ($colleges as $college)
                                <option value="{{ $college->id }}" {{ (old('college', $dean) == $college->id) ? 'selected' : '' }}>{{ $college->name }}</option>  
                            @endforeach --}}
                        </select>
                        <x-jet-input-error for="college"></x-jet-input-error>
                      </div>  
                      
                      <div class="form-group sector-input" style="@if($sectorhead == null) display: none; @endif">
                        <x-jet-label value="{{ __('Sectors') }}" />
                        <select name="sector[]" id="sector" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                            {{-- @foreach ($sectors as $sector)
                                <option value="{{ $sector->id }}" {{ (old('sector', $sectorhead) == $sector->id) ? 'selected' : '' }}>{{ $sector->name }}</option>  
                            @endforeach --}}
                        </select>
                        <x-jet-input-error for="sector"></x-jet-input-error>
                      </div>  

                      <div class="form-group extension-input" style="@if($extensionist == null) display: none; @endif">
                        <x-jet-label value="{{ __('Extensionist of:') }}" />
                        <select name="extension[]" id="extension" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                        </select>
                        <x-jet-input-error for="extension"></x-jet-input-error>
                      </div>  

                      <div class="form-group research-input" style="@if($researcher == null) display: none; @endif">
                        <x-jet-label value="{{ __('Researcher of:') }}" />
                        <select name="research[]" id="research" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                        </select>
                        <x-jet-input-error for="research"></x-jet-input-error>
                      </div> 
                      <div class="form-group d-flex justify-content-end align-items-baseline">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mr-3" tabindex="-1" role="button" aria-disabled="true">Cancel</a>
                        <button type="submit" class="btn btn-success">Save</button>
                      </div> 
                    </div>
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
              maxItems: null,
              valueField: 'value',
              labelField: 'text',
              sortField: "text",
              options: @json($departments),
              items: @json($chairperson),
          });
          $("#college").selectize({
              maxItems: null,
              valueField: 'value',
              labelField: 'text',
              sortField: "text",
              options: @json($colleges),
              items: @json($dean),
              
          });
          $("#sector").selectize({
              maxItems: null,
              sortField: "text",
              valueField: 'value',
              labelField: 'text',
              options: @json($sectors),
              items: @json($sectorhead),
          });
          $("#extension").selectize({
              maxItems: null,
              sortField: "text",
              valueField: 'value',
              labelField: 'text',
              options: @json($colleges),
              items: @json($extensionist),
          });
          $("#research").selectize({
              maxItems: null,
              sortField: "text",
              valueField: 'value',
              labelField: 'text',
              options: @json($colleges),
              items: @json($researcher),
          });

          $('.role-checkbox').on('change', function() {
              var id = $(this).data('id');
              if(id == 5){
                changeDeptDisp(id);
              }
              if(id == 6){
                changeCollegeDisp(id);
              }
              if(id == 7){
                changeSectorDisp(id);
              }
              if(id == 10){
                changeResearchDisp(id);
              }
              if(id == 11){
                changeExtensionDisp(id);
              }
          });

          function changeCollegeDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.college-input').show();
                 $('#college').removeAttr('disabled');
                 $('#college').attr('required', true);
              }
              else{
                $('.college-input').hide();
                $('#college').removeAttr('required');
                $('#college').attr('disabled', true);
              }
          }

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

          function changeSectorDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.sector-input').show();
                 $('#sector').removeAttr('disabled');
                 $('#sector').attr('required', true);
              }
              else{
                $('.sector-input').hide();
                $('#sector').removeAttr('required');
                $('#sector').attr('disabled', true);
              }
          }
          function changeExtensionDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.extension-input').show();
                 $('#extension').removeAttr('disabled');
                 $('#extension').attr('required', true);
              }
              else{
                $('.extension-input').hide();
                $('#extension').removeAttr('required');
                $('#extension').attr('disabled', true);
              }
          }
          function changeResearchDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.research-input').show();
                 $('#research').removeAttr('disabled');
                 $('#research').attr('required', true);
              }
              else{
                $('.research-input').hide();
                $('#research').removeAttr('required');
                $('#research').attr('disabled', true);
              }
          }

          $(function (){
              if($('#role-5').is(':checked')){
                  changeDeptDisp(5);
              }
              if($('#role-6').is(':checked')){
                  changeCollegeDisp(6);
              }
              if($('#role-7').is(':checked')){
                  changeSectorDisp(7);
              }
              if($('#role-11').is(':checked')){
                  changeExtensionDisp(11);
              }
              if($('#role-10').is(':checked')){
                  changeResearchDisp(10);
              }
          });
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