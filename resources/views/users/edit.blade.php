<x-app-layout>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-9 offset-md-2">
          <div class="d-flex align-content-center">
            <h3 class="font-weight-bold ml-3">Edit User Access</h3>
            <p class="mt-1 ml-3">
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

                      <div class="form-group faculty-input" style="@if($employeeColleges['F'] == null) display: none; @endif">
                        <x-jet-label value="{{ __('Faculty - College/Branch/Campus') }}" />
                        <select name="facultyCollege[]" id="facultyCollege" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                            {{-- @foreach ($employeeColleges['F'] as $facultyCollege)
                                <option value="{{ $facultyCollege->id }}" {{ (old('facultyCollege', $facultyCollege) == $facultyCollege->id) ? 'selected' : '' }}>{{ $facultyCollege->name }}</option>
                            @endforeach --}}

                        </select>
                        <span class="text-danger" id="faculty_error">This is required</span>
                        <x-jet-input-error for="facultyCollege"></x-jet-input-error>
                      </div>

                      <div class="form-group admin-input" style="@if($employeeColleges['A'] == null) display: none; @endif">
                        <x-jet-label value="{{ __('Admin - College/Branch/Campus/Office') }}" />
                        <select name="adminOffice[]" id="adminOffice" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                            {{-- @foreach ($employeeColleges['A'] as $adminOffice)
                                <option value="{{ $adminOffice->id }}" {{ (old('adminOffice', $adminOffice) == $adminOffice->id) ? 'selected' : '' }}>{{ $adminOffice->name }}</option>
                            @endforeach --}}

                        </select>
                        <span class="text-danger" id="admin_error">This is required</span>
                        <x-jet-input-error for="adminOffice"></x-jet-input-error>
                      </div>

                      <div class="form-group department-input" style="@if($chairperson == null) display: none; @endif">
                        <x-jet-label value="{{ __('Chairperson/Chief - Department/Section') }}" />
                        <select name="department[]" id="department" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                            {{-- @foreach ($departments as $department)
                                <option value="{{ $department->id }}" {{ (old('department', $chairperson) == $department->id) ? 'selected' : '' }}>{{ $department->name }}</option>
                            @endforeach --}}

                        </select>
                        <span class="text-danger" id="chairperson_error">This is required</span>
                        <x-jet-input-error for="department"></x-jet-input-error>
                      </div>

                      <div class="form-group college-input" style="@if($dean == null) display: none; @endif">
                        <x-jet-label value="{{ __('Dean/Director - Office/College/Branch/Campus') }}" />
                        <select name="college[]" id="college" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                            {{-- @foreach ($colleges as $college)
                                <option value="{{ $college->id }}" {{ (old('college', $dean) == $college->id) ? 'selected' : '' }}>{{ $college->name }}</option>
                            @endforeach --}}
                        </select>
                        <span class="text-danger" id="dean_error">This is required</span>
                        <x-jet-input-error for="college"></x-jet-input-error>
                      </div>

                      <div class="form-group sector-input" style="@if($sectorhead == null) display: none; @endif">
                        <x-jet-label value="{{ __('Sectors/VP') }}" />
                        <select name="sector[]" id="sector" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                            {{-- @foreach ($sectors as $sector)
                                <option value="{{ $sector->id }}" {{ (old('sector', $sectorhead) == $sector->id) ? 'selected' : '' }}>{{ $sector->name }}</option>
                            @endforeach --}}
                        </select>
                        <span class="text-danger" id="sector_error">This is required</span>
                        <x-jet-input-error for="sector"></x-jet-input-error>
                      </div>

                      <div class="form-group extension-input" style="@if($extensionist == null) display: none; @endif">
                        <x-jet-label value="{{ __('Extensionist of:') }}" />
                        <select name="extension[]" id="extension" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                        </select>
                        <span class="text-danger" id="extensionist_error">This is required</span>
                        <x-jet-input-error for="extension"></x-jet-input-error>
                      </div>

                      <div class="form-group research-input" style="@if($researcher == null) display: none; @endif">
                        <x-jet-label value="{{ __('Researcher of:') }}" />
                        <select name="research[]" id="research" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                        </select>
                        <span class="text-danger" id="researcher_error">This is required</span>
                        <x-jet-input-error for="research"></x-jet-input-error>
                      </div>

                      <div class="form-group associate-input" style="@if($associateDeanDirector == null) display: none; @endif">
                        <x-jet-label value="{{ __('Associate/Assistant Dean/Director of:') }}" />
                        <select name="collegeAssociate[]" id="collegeAssociate" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                        </select>
                        <span class="text-danger" id="associate_error">This is required</span>
                        <x-jet-input-error for="collegeAssociate"></x-jet-input-error>
                      </div>

                      <div class="form-group assistant-input" style="@if($assistantVP == null) display: none; @endif">
                        <x-jet-label value="{{ __('Assistant to VP of:') }}" />
                        <select name="sectorAssistant[]" id="sectorAssistant" class="form-control form-control-md">
                            <option value="" selected>Choose...</option>
                        </select>
                        <span class="text-danger" id="assistant_error">This is required</span>
                        <x-jet-input-error for="sectorAssistant"></x-jet-input-error>
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
        $(function() {
            $('#department_error').hide();
            $('#dean_error').hide();
            $('#sector_error').hide();
            $('#extensionist_error').hide();
            $('#researcher_error').hide();
            $('#associate_error').hide();
            $('#assistant_error').hide();
            $('#faculty_error').hide();
            $('#admin_error').hide();
        });
          $("#facultyCollege").selectize({
              maxItems: null,
              valueField: 'value',
              labelField: 'text',
              sortField: "text",
              options: @json($colleges),
              items: @json($employeeColleges['F']),
          });
          $("#adminOffice").selectize({
              maxItems: null,
              valueField: 'value',
              labelField: 'text',
              sortField: "text",
              options: @json($colleges),
              items: @json($employeeColleges['A']),
          });
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
          $("#collegeAssociate").selectize({
              maxItems: null,
              valueField: 'value',
              labelField: 'text',
              sortField: "text",
              options: @json($colleges),
              items: @json($associateDeanDirector),
          });
          $("#sectorAssistant").selectize({
              maxItems: null,
              valueField: 'value',
              labelField: 'text',
              sortField: "text",
              options: @json($sectors),
              items: @json($assistantVP),
          });

          $('.role-checkbox').on('change', function() {
              var id = $(this).data('id');
              if(id == 1){
                changeFacultyCollegeDisp(id);
              }
              if(id == 3){
                changeAdminOfficeDisp(id);
              }
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
              if(id == 12){
                changeCollegeAssociateDisp(id);
              }
              if(id == 13){
                changeSectorAssistantDisp(id);
              }
          });

          function changeFacultyCollegeDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.faculty-input').show();
                 $('#facultyCollege').removeAttr('disabled');
                 $('#facultyCollege').attr('required', true);
                $('#faculty_error').show();

              }
              else{
                $('.faculty-input').hide();
                $('#facultyCollege').removeAttr('required');
                $('#facultyCollege').attr('disabled', true);
                $('#faculty_error').hide();
              }
          }

          function changeAdminOfficeDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.admin-input').show();
                 $('#adminOffice').removeAttr('disabled');
                 $('#adminOffice').attr('required', true);
                $('#admin_error').show();

              }
              else{
                $('.admin-input').hide();
                $('#adminOffice').removeAttr('required');
                $('#adminOffice').attr('disabled', true);
                $('#admin_error').hide();
              }
          }

          function changeCollegeDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.college-input').show();
                 $('#college').removeAttr('disabled');
                 $('#college').attr('required', true);
                $('#dean_error').show();

              }
              else{
                $('.college-input').hide();
                $('#college').removeAttr('required');
                $('#college').attr('disabled', true);
                $('#dean_error').hide();
              }
          }

          function changeDeptDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.department-input').show();
                 $('#department').removeAttr('disabled');
                 $('#department').attr('required', true);
                 $('#department_error').show();

              }
              else{
                $('.department-input').hide();
                $('#department').removeAttr('required');
                $('#department').attr('disabled', true);
                $('#department_error').hide();
              }
          }

          function changeSectorDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.sector-input').show();
                 $('#sector').removeAttr('disabled');
                 $('#sector').attr('required', true);
                 $('#sector_error').show();

              }
              else{
                $('.sector-input').hide();
                $('#sector').removeAttr('required');
                $('#sector').attr('disabled', true);
                $('#sector_error').hide();
              }
          }
          function changeExtensionDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.extension-input').show();
                 $('#extension').removeAttr('disabled');
                 $('#extension').attr('required', true);
                 $('#extensionist_error').show();

              }
              else{
                $('.extension-input').hide();
                $('#extension').removeAttr('required');
                $('#extension').attr('disabled', true);
                $('#extensionist_error').hide();
              }
          }
          function changeResearchDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.research-input').show();
                 $('#research').removeAttr('disabled');
                 $('#research').attr('required', true);
                 $('#researcher_error').show();
              }
              else{
                $('.research-input').hide();
                $('#research').removeAttr('required');
                $('#research').attr('disabled', true);
                $('#researcher_error').hide();
              }
          }

          function changeCollegeAssociateDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.associate-input').show();
                 $('#collegeAssociate').removeAttr('disabled');
                 $('#collegeAssociate').attr('required', true);
                $('#associate_error').show();

              }
              else{
                $('.associate-input').hide();
                $('#collegeAssociate').removeAttr('required');
                $('#collegeAssociate').attr('disabled', true);
                $('#associate_error').hide();
              }
          }

          function changeSectorAssistantDisp(id){
              if( $('#role-'+id).is(':checked')){
                 $('.assistant-input').show();
                 $('#sectorAssistant').removeAttr('disabled');
                 $('#sectorAssistant').attr('required', true);
                $('#assistant_error').show();

              }
              else{
                $('.assistant-input').hide();
                $('#sectorAssistant').removeAttr('required');
                $('#sectorAssistant').attr('disabled', true);
                $('#assistant_error').hide();
              }
          }

          $(function (){
              if($('#role-1').is(':checked')){
                  changeFacultyCollegeDisp(1);
              }
              if($('#role-3').is(':checked')){
                  changeadminOfficeDisp(3);
              }
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
              if($('#role-12').is(':checked')){
                changeCollegeAssociateDisp(12);
              }
              if($('#role-13').is(':checked')){
                changeSectorAssistantDisp(13);
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
