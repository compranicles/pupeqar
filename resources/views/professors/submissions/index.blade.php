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
                        @if ($message = Session::get('success_submission'))
                            <div class="alert alert-success">
                                {{ $message }}
                            </div>
                        @endif
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
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    B.1. Faculty Outstanding Achievements/Awards
                                                </option>   
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    B.2. Officership/Membership in Professional Organization/s
                                                </option>   
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    B.3.1 Attendance in Relevant Faculty Development Program (Seminars/Webinars, Fora/Conferences) 
                                                </option>   
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    B.3.2. Attendance in Training/s
                                                </option>   
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    C.1. Research Ongoing /Completed
                                                </option>   
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    C.2. Research Publication
                                                </option>   
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    C.3. Research Presentation
                                                </option>   
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    C.4. Research Citation
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    C.5. Research Utilization
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    C.6.  Copyrighted Research Output
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    D.1. Faculty Invention, Innovation and Creative Works Commitment
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    E.1. Expert Service Rendered - as a Consultant/Expert
                                                </option>
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    E.1. Expert Service Rendered - in Conferences
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    E.1. Expert Service Rendered - in Academic Journals
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    E.2. Extension Program, Project and Activity (Ongoing and Completed)
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    E.3. Partnership/Linkages/Network
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    E.4. Faculty Involvement in Inter-Country Mobility
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    F.1.  Material, Reference/Text Book, Module, Monographs
                                                </option>
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    F.2. Course Syllabus/Guide Developed/Revised/Enhanced
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    III. Special Tasks
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    III. Special Tasks - Commitment Measurable by Efficiency
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    III. Special Tasks - Commitment Measurable by Timeliness
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    IV. Attendance in University Function
                                                </option>  
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    V. Viable Demonstration Projects
                                                </option>
                                                <option value="" {{ ((old('form_type') == 1) ? 'selected' : '' )}}>
                                                    VI.  Awards/Recognitions Received by College/Branch/Campus from Reputable Organizations																								
                                                </option>  
                                            </select>
        
                                            <x-jet-input-error for="form_type"></x-jet-input-error>
                                        </div>  
                                    </div>
                                </div>
                                <div class="col-lg-3">
                                    <div class="d-flex flex-column">
                                        <button class="btn btn-lg btn-outline-success" type="submit"><i class="fas fa-plus mr-2"></i>Create Submission</button>
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
                                <div class="table-responsive">
                                    <table class="table " id="submission_table">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Form Submitted</th>
                                                <th>Date Submitted</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($submissions as $submission)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        @switch($submission->form_name)
                                                            @case('ongoingadvanced')
                                                                <a href="{{ route('professor.submissions.ongoingadvanced.show', $submission->form_id) }}">
                                                                    Ongoing Advanced/Professional Study
                                                                </a>
                                                                @break
                                                            @default
                                                        @endswitch
                                                    </td>
                                                    <td>{{ date("F j, Y, g:i a" , strtotime($submission->created_at)) }}</td>
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
                                                    <td>
                                                        
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