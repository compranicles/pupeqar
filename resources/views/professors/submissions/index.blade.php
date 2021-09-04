<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Submissions') }}
        </h2>
    </x-slot>
    <div class="container">
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('professor.submissions.select') }}" method="POST">
                            @csrf
                            <div class="row mb-n1">
                                <div class="col-lg-9">
                                    <div class="d-flex flex-column mt-1 mb-1">
                                        <div class="form-group">
                                            {{-- <x-jet-label value="{{ __('Select Form to Submit') }}" /> --}}
                                            <select name="form_type" class="form-control-lg custom-select" id="form_type" {{ $errors->has('form_type') ? 'is-invalid' : '' }} autofocus autocomplete="form_type">
                                                <option value="" selected disabled>Select Form To Submit... </option>
                                                <option value="ongoingadvanced" {{ ((old('form_type') == 'ongoingadvanced') ? 'selected' : '' )}}>
                                                    A. Ongoing Advanced/Professional Study
                                                </option>   
                                                <option value="facultyaward" {{ ((old('form_type') == "facultyaward") ? 'selected' : '' )}}>
                                                    B.1. Faculty Outstanding Achievements/Awards
                                                </option>
                                                <option value="officership" {{ ((old('form_type') == "officership") ? 'selected' : '' )}}>
                                                    B.2. Officership/Membership in Professional Organization/s
                                                </option>   
                                                <option value="attendanceconference" {{ ((old('form_type') == "attendanceconference") ? 'selected' : '' )}}>
                                                    B.3.1 Attendance in Relevant Faculty Development Program (Seminars/Webinars, Fora/Conferences) 
                                                </option>   
                                                <option value="attendancetraining" {{ ((old('form_type') == "attendancetraining") ? 'selected' : '' )}}>
                                                    B.3.2. Attendance in Training/s
                                                </option>   
                                                <option value="research" {{ ((old('form_type') == "research") ? 'selected' : '' )}}>
                                                    C.1. Research Ongoing/Completed
                                                </option>   
                                                <option value="researchpublication" {{ ((old('form_type') == "researchpublication") ? 'selected' : '' )}}>
                                                    C.2. Research Publication
                                                </option>   
                                                <option value="researchpresentation" {{ ((old('form_type') == "researchpresentation") ? 'selected' : '' )}}>
                                                    C.3. Research Presentation
                                                </option>   
                                                <option value="researchcitation" {{ ((old('form_type') == "researchcitation") ? 'selected' : '' )}}>
                                                    C.4. Research Citation
                                                </option>  
                                                <option value="researchutilization" {{ ((old('form_type') == "researchutilization") ? 'selected' : '' )}}>
                                                    C.5. Research Utilization
                                                </option>  
                                                <option value="researchcopyright" {{ ((old('form_type') == "researchcopyright") ? 'selected' : '' )}}>
                                                    C.6.  Copyrighted Research Output
                                                </option>  
                                                <option value="invention" {{ ((old('form_type') == "invention") ? 'selected' : '' )}}>
                                                    D.1. Faculty Invention, Innovation and Creative Works Commitment
                                                </option>  
                                                <option value="expertconsultant" {{ ((old('form_type') == "expertconsultant") ? 'selected' : '' )}}>
                                                    E.1. Expert Service Rendered - as a Consultant/Expert
                                                </option>
                                                <option value="expertconference" {{ ((old('form_type') == "expertconference") ? 'selected' : '' )}}>
                                                    E.1. Expert Service Rendered - in Conferences
                                                </option>  
                                                <option value="expertjournal" {{ ((old('form_type') == "expertjournal") ? 'selected' : '' )}}>
                                                    E.1. Expert Service Rendered - in Academic Journals
                                                </option>  
                                                <option value="extensionprogram" {{ ((old('form_type') == 'extensionprogram') ? 'selected' : '' )}}>
                                                    E.2. Extension Program, Project and Activity (Ongoing and Completed)
                                                </option>  
                                                <option value="partnership" {{ ((old('form_type') == "partnership") ? 'selected' : '' )}}>
                                                    E.3. Partnership/Linkages/Network
                                                </option>  
                                                <option value="facultyintercountry" {{ ((old('form_type') == "facultyintercountry") ? 'selected' : '' )}}>
                                                    E.4. Faculty Involvement in Inter-Country Mobility
                                                </option>  
                                                <option value="material" {{ ((old('form_type') == "material") ? 'selected' : '' )}}>
                                                    F.1.  Material, Reference/Text Book, Module, Monographs
                                                </option>
                                                <option value="syllabus" {{ ((old('form_type') == "syllabus") ? 'selected' : '' )}}>
                                                    F.2. Course Syllabus/Guide Developed/Revised/Enhanced
                                                </option>  
                                                <option value="specialtask" {{ ((old('form_type') == "specialtask") ? 'selected' : '' )}}>
                                                    III. Special Tasks
                                                </option>  
                                                <option value="specialtaskefficiency" {{ ((old('form_type') == "specialtaskefficiency") ? 'selected' : '' )}}>
                                                    III. Special Tasks - Commitment Measurable by Efficiency
                                                </option>  
                                                <option value="specialtasktimeliness" {{ ((old('form_type') == "specialtasktimeliness") ? 'selected' : '' )}}>
                                                    III. Special Tasks - Commitment Measurable by Timeliness
                                                </option>  
                                                <option value="attendancefunction" {{ ((old('form_type') == "attendancefunction") ? 'selected' : '' )}}>
                                                    IV. Attendance in University Function
                                                </option>  
                                                <option value="viableproject" {{ ((old('form_type') == "viableproject") ? 'selected' : '' )}}>
                                                    V. Viable Demonstration Projects
                                                </option>
                                                <option value="branchaward" {{ ((old('form_type') == "branchaward") ? 'selected' : '' )}}>
                                                    VI.  Awards/Recognitions Received by College/Branch/Campus from Reputable Organizations																								
                                                </option>  
                                            </select>
        
                                            <x-jet-input-error for="form_type"></x-jet-input-error>
                                        </div>  
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="d-flex flex-column">
                                        <button class="btn btn-lg btn-success" type="submit"><i class="fas fa-plus mr-2"></i>Create Submission</button>
                                    </div>   
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="row"> 
                            <div class="col-lg-12">
                                @if ($message = Session::get('success_submission'))
                                    <div class="alert alert-success">
                                        {{ $message }}
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table " id="submission_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Form Submitted</th>
                                                <th>Date Submitted</th>
                                                <th>Date Modified</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($submissions as $submission)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td class="text-wrap" style="width: 30rem">
                                                        @switch($submission->form_name)
                                                            @case('ongoingadvanced')
                                                                <a href="{{ route('professor.submissions.ongoingadvanced.show', $submission->form_id) }}">
                                                                    A. Ongoing Advanced/Professional Study
                                                                </a>
                                                                @break
                                                            @case('facultyaward')
                                                                <a href="{{ route('professor.submissions.facultyaward.show', $submission->form_id) }}">
                                                                    B.1. Faculty Outstanding Achievements/Awards
                                                                </a>
                                                            @break
                                                            @case('officership')
                                                                <a href="{{ route('professor.submissions.officership.show', $submission->form_id) }}">
                                                                    B.2 Officership/Membership in Professional Organization/s
                                                                </a>
                                                            @break
                                                            @case('attendanceconference')
                                                                <a href="{{ route('professor.submissions.attendanceconference.show', $submission->form_id) }}">
                                                                    B.3.1. Attendance in Relevant Faculty Development Program
                                                                </a>
                                                            @break  
                                                            @case('attendancetraining')
                                                                <a href="{{ route('professor.submissions.attendancetraining.show', $submission->form_id) }}">
                                                                    B.3.2. Attendance in Training/s
                                                                </a>
                                                            @break
                                                            @case('research')
                                                                <a href="{{ route('professor.submissions.research.show', $submission->form_id) }}">
                                                                    C.1. Research Ongoing/Completed
                                                                </a>
                                                            @break
                                                            @case('researchpublication')
                                                                <a href="{{ route('professor.submissions.researchpublication.show', $submission->form_id) }}">
                                                                    C.2. Research Publication
                                                                </a>
                                                            @break 
                                                            @case('researchpresentation')
                                                                <a href="{{ route('professor.submissions.researchpresentation.show', $submission->form_id) }}">
                                                                    C.3. Research Presentation
                                                                </a>
                                                            @break 
                                                            @case('researchcitation')
                                                                <a href="{{ route('professor.submissions.researchcitation.show', $submission->form_id) }}">
                                                                    C.4. Research Citation
                                                                </a>
                                                            @break 
                                                            @case('researchutilization')
                                                                <a href="{{ route('professor.submissions.researchutilization.show', $submission->form_id) }}">
                                                                    C.5. Research Utilization
                                                                </a>
                                                            @break 
                                                            @case('researchcopyright')
                                                                <a href="{{ route('professor.submissions.researchcopyright.show', $submission->form_id) }}">
                                                                    C.6.  Copyrighted Research Output
                                                                </a>
                                                            @break 
                                                            @case('invention')
                                                                <a href="{{ route('professor.submissions.invention.show', $submission->form_id) }}">
                                                                    D.1. Faculty Invention, Innovation and Creative Works Commitment
                                                                </a>
                                                            @break 
                                                            @case('expertconsultant')
                                                                <a href="{{ route('professor.submissions.expertconsultant.show', $submission->form_id) }}">
                                                                    E.1. Expert Service Rendered - as a Consultant/Expert
                                                                </a>
                                                            @break 
                                                            @case('expertconference')
                                                                <a href="{{ route('professor.submissions.expertconference.show', $submission->form_id) }}">
                                                                    E.1. Expert Service Rendered - in Conferences
                                                                </a>
                                                            @break 
                                                            @case('expertjournal')
                                                                <a href="{{ route('professor.submissions.expertjournal.show', $submission->form_id) }}">
                                                                    E.1. Expert Service Rendered - in Academic Journals
                                                                </a>
                                                            @break 
                                                            @case('extensionprogram')
                                                                <a href="{{ route('professor.submissions.extensionprogram.show', $submission->form_id) }}">
                                                                    E.2. Extension Program, Project and Activity (Ongoing and Completed)
                                                                </a>
                                                            @break 
                                                            @case('partnership')
                                                            <a href="{{ route('professor.submissions.partnership.show', $submission->form_id) }}">
                                                                    E.3. Partnership/Linkages/Network
                                                            </a>
                                                            @break 
                                                            @case('facultyintercountry')
                                                                <a href="{{ route('professor.submissions.facultyintercountry.show', $submission->form_id) }}">
                                                                    E.4. Faculty Involvement in Inter-Country Mobility
                                                                </a>
                                                            @break 
                                                            @case('material')
                                                                <a href="{{ route('professor.submissions.material.show', $submission->form_id) }}">
                                                                    F.1. Instructional Material, Reference/Text Book, Module, Monographs
                                                                </a>
                                                            @break 
                                                            @case('syllabus')
                                                                <a href="{{ route('professor.submissions.syllabus.show', $submission->form_id) }}">
                                                                    F.2. Course Syllabus/Guide Developed/Revised/Enhanced
                                                                </a>
                                                            @break 
                                                            @case('specialtask')
                                                                <a href="{{ route('professor.submissions.specialtask.show', $submission->form_id) }}">
                                                                    III. Special Tasks
                                                                </a>
                                                            @break 
                                                            @case('specialtaskefficiency')
                                                                <a href="{{ route('professor.submissions.specialtaskefficiency.show', $submission->form_id) }}">
                                                                    III. Special Tasks - Commitment Measurable by Efficiency
                                                                </a>
                                                            @break 
                                                            @case('specialtasktimeliness')
                                                                <a href="{{ route('professor.submissions.specialtasktimeliness.show', $submission->form_id) }}">
                                                                    III. Special Tasks - Commitment Measurable by Timeliness
                                                                </a>
                                                            @break 
                                                            @case('attendancefunction')
                                                                <a href="{{ route('professor.submissions.attendancefunction.show', $submission->form_id) }}">
                                                                    IV. Attendance in University Function
                                                                </a>
                                                            @break 
                                                            @case('viableproject')
                                                                <a href="{{ route('professor.submissions.viableproject.show', $submission->form_id) }}">
                                                                    V. Viable Demonstration Projects
                                                                </a>
                                                            @break 
                                                            @case('branchaward')
                                                                <a href="{{ route('professor.submissions.branchaward.show', $submission->form_id) }}">
                                                                    VI.  Awards/Recognitions Received by College/Branch/Campus from Reputable Organizations																								
                                                                </a>
                                                            @break 
                                                            @default
                                                        @endswitch
                                                    </td>
                                                    <td>{{ date("M j, Y, g:i a" , strtotime($submission->created_at)) }}</td>
                                                    <td>{{ date("M j, Y, g:i a" , strtotime($submission->updated_at)) }}</td>
                                                    <td>
                                                        @switch($submission->status)
                                                            @case(1)
                                                                Not Reviewed
                                                                @break
                                                            @case(2)
                                                                <span class="text-success">
                                                                    Accepted
                                                                </span>
                                                                @break
                                                            @case(3)
                                                                <span class="text-danger">
                                                                    Rejected
                                                                </span>
                                                                @break
                                                            @default
                                                        @endswitch
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.25/datatables.min.js"></script>
    <script>
        $(document).ready( function () {
            $('#submission_table').DataTable({
            });
        } );
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