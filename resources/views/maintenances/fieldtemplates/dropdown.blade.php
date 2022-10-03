{{-- fieldInfo --}}

<div class="{{ $fieldInfo->size }} {{ $fieldInfo->name }} mb-2">
    <div class="form-group">
        <label class="font-weight-bold" for="{{ $fieldInfo->name }}" >{{ $fieldInfo->label }}</label><span style='color: red'>{{ ($fieldInfo->required == 1) ? " *" : '' }}</span>
        @if (isset($fieldInfo->h_r_i_s_form_id))
            @if ($fieldInfo->h_r_i_s_form_id == 4 && $fieldInfo->name == 'type')
            <span>
                <i class="bi bi-info-circle-fill text-primary" style="font-size: 1.10em;" role="button" data-bs-toggle="modal" data-bs-target="#typeDefinition"></i>
            </span>    
            @endif
            @if ($fieldInfo->h_r_i_s_form_id == 3 && $fieldInfo->name == 'level')
            <br>
            <span class="form-notes">
                Select level of the organization.
            </span>
            @endif
            @if ($fieldInfo->h_r_i_s_form_id == 2 && $fieldInfo->name == 'level')
            <br>
            <span class="form-notes">
                Select level of achievement/award.
            </span>
            @endif
        @endif
        @if ($fieldInfo->name == 'classification_of_person')
        <br>
        <span class="form-notes">
            Please select your designation.
        </span>
        @endif
        <select name="{{ $fieldInfo->name }}" id="{{ $fieldInfo->name }}" class="{{ $errors->has($fieldInfo->name) ? 'is-invalid' : '' }} form-control custom-select form-validation {{ $fieldInfo->name }}" {{ ($fieldInfo->required == 1) ? 'required' : '' }}
            @switch($fieldInfo->visibility)
                    @case(2)
                        {{ 'readonly' }}
                        @break
                    @case(3)
                        {{ 'disabled' }}
                        @break
                    @case(4)
                        {{ 'hidden' }}
                        @break
                    @default

                @endswitch>

            <option value="" selected disabled>Choose...</option>
            @isset($dropdown_options[$fieldInfo->name])
                @foreach($dropdown_options[$fieldInfo->name] as $option)
                    <option value="{{ $option->id }}" {{ (old($fieldInfo->name, $value ) == $option->id) ? 'selected' : '' }}>{{ $option->name }}</option>
                @endforeach
            @endisset
            @if (isset($fieldInfo->h_r_i_s_form_id))
            @if ($fieldInfo->h_r_i_s_form_id == 4 && $fieldInfo->name == 'fund_source')
                <span class="ml-3" role="alert">
                    <option value="0" {{ (old($fieldInfo->name, $value ) == 0) ? 'selected' : '' }}>Not a Paid Seminar/Training</option>
                </span>
            @endif
            @if ($fieldInfo->h_r_i_s_form_id == 1 && $fieldInfo->name != 'level')
                <span class="ml-3" role="alert">
                    <option value="0" {{ (old($fieldInfo->name, $value ) == 0) ? 'selected' : '' }}>Not Applicable</option>
                </span>
            @endif
        @endif

        </select>

        @if (isset($fieldInfo->research_form_id))
            @if ($fieldInfo->name == 'status' && $fieldInfo->research_form_id == 1)
            <span class="form-notes">
                <p>Please select <strong>new commitment</strong> or <strong>ongoing</strong> before proceeding for completion, etc.</p>
            </span>
            @endif
        @endif
        @if ($fieldInfo->name == 'classification_of_person')
        <span class="form-notes">
            <p>Chair/Chief/Dean/Director will encode for <strong>student</strong> mobility.</p>
        </span>
        @endif

        @error($fieldInfo->name)
            <span class='invalid-feedback' role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>

<div class="modal fade" id="typeDefinition" tabindex="-1" aria-labelledby="typeDefinitionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Types of Trainings & Seminars</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <p>
                    <strong>Executive/Managerial.</strong>
                    Trainings that are in the Executive/Managerial type includes professional, technical and 
                    scientific positions, the functions of which are managerial in character. Specifically, 
                    it is about exercising management over people, resource, and/or policy and exercising 
                    functions such as planning, organizing, directing, coordinating, controlling, overseeing 
                    the activities of an organization, a unit thereof or of a group, requiring some degrees 
                    of professional, technical or scientific knowledge and experience, application of 
                    managerial skills required to carry out basic duties and responsibilities involving 
                    leadership, functional guidance and control. These positions require intensive and through 
                    knowledge of a specialized field. <br>
                </p>
                <p>
                    <strong>Supervisory.</strong>
                    Trainings that are in the Supervisory type are professional, technical and scientific 
                    trainings which have the responsibillity of overseeing the work of an organizational 
                    unit charged with a major and specialized activity. This includes tasks of a supervisor 
                    that plans, programs, delegates tasks and evaluates performance of employees; monitors 
                    work outputs; maintains moral and discipline among employees; develops cooperation and 
                    ensure a well-coordinated workforce; and coordinates and cooperates with other 
                    organizational units.<br>
                </p>
                <p>
                    <strong>Technical.</strong>
                    Refers to substantive programs in specific/technical/scientific/areas for enhancement 
                    of skills and knowledge of second level personnel in the career service.<br>
                </p>
                <p>
                    <strong>Foundation.</strong>
                    Foundation trainings are Learning and Development interventions that includes the 
                    induction program, orientation program or value development program.
                </p>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        @if(!isset($dropdown_options[$fieldInfo->name]))
        setTimeout(function (){

            $.ajax('{{ route('dropdowns.options', $fieldInfo->dropdown_id) }}',   // request url
            {
                success: function (data, status, xhr) {    // success callback function
                    data.forEach(function (item){
                        $("#{{ $fieldInfo->name }}").append(new Option(item.name, item.id)).change();
                    });
                    var value = "{{ $value }}";
                    if (value != ''){
                        $("#{{ $fieldInfo->name }}").val("{{ $value }}");
                    }
                    <?php if (old($fieldInfo->name) != '') { ?>
                        $("#{{ $fieldInfo->name }}").val("{{ old($fieldInfo->name) }}");
                    <?php } ?>
                }
            });
        }, Math.floor(Math.random() * (2500 - 1) + 1));
        @endif

    </script>
@endpush
