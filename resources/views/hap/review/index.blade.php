<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Review Submissions') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <ul class="nav nav-pills">
                                    <li class="nav-item pill-1">
                                      <a class="nav-link {{ ($keyword == '') ? 'active' : '' }}" href="{{ route('hap.review.index') }}">Not Reviewed</a>
                                    </li>
                                    <li class="nav-item pill-1">
                                      <a class="nav-link {{ ($keyword == 'accepted') ? 'active' : '' }}" href="{{ route('hap.review.index', 'status=accepted') }}">Accepted</a>
                                    </li>
                                    <li class="nav-item pill-1">
                                      <a class="nav-link {{ ($keyword == 'rejected') ? 'active' : '' }}" href="{{ route('hap.review.index', 'status=rejected') }}">Rejected</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive pt-1">
                                    <table class="table table-hover" id="review_table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Accomplishment</th>
                                                <th>Submitted by</th>
                                                <th>Date Submitted</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($submissions as $submission)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="text-wrap" style="width: 30rem">
                                                    @switch($submission->form_name)
                                                        @case('ongoingadvanced')
                                                            <a href="{{ route('hap.review.ongoingadvanced.show', $submission->form_id) }}">
                                                                A. Ongoing Advanced/Professional Study
                                                            </a>
                                                        @break
                                                        @case('facultyaward')
                                                            <a href="{{ route('hap.review.facultyaward.show', $submission->form_id) }}">
                                                                B.1. Faculty Outstanding Achievements/Awards
                                                            </a>
                                                        @break
                                                        @case('officership')
                                                            <a href="{{ route('hap.review.officership.show', $submission->form_id) }}">
                                                                B.2 Officership/Membership in Professional Organization/s
                                                            </a>
                                                        @break
                                                        @case('attendanceconference')
                                                            <a href="{{ route('hap.review.attendanceconference.show', $submission->form_id) }}">
                                                                B.3.1. Attendance in Relevant Faculty Development Program
                                                            </a>
                                                        @break  
                                                        @case('attendancetraining')
                                                            <a href="{{ route('hap.review.attendancetraining.show', $submission->form_id) }}">
                                                                B.3.2. Attendance in Training/s
                                                            </a>
                                                        @break
                                                        @case('research')
                                                            <a href="{{ route('hap.review.research.show', $submission->form_id) }}">
                                                                C.1. Research Ongoing/Completed
                                                            </a>
                                                        @break
                                                        @case('researchpublication')
                                                            <a href="{{ route('hap.review.researchpublication.show', $submission->form_id) }}">
                                                                C.2. Research Publication
                                                            </a>
                                                        @break 
                                                        @case('researchpresentation')
                                                            <a href="{{ route('hap.review.researchpresentation.show', $submission->form_id) }}">
                                                                C.3. Research Presentation
                                                            </a>
                                                        @break 
                                                        @case('researchcitation')
                                                            <a href="{{ route('hap.review.researchcitation.show', $submission->form_id) }}">
                                                                C.4. Research Citation
                                                            </a>
                                                        @break 
                                                        @case('researchutilization')
                                                            <a href="{{ route('hap.review.researchutilization.show', $submission->form_id) }}">
                                                                C.5. Research Utilization
                                                            </a>
                                                        @break 
                                                        @case('researchcopyright')
                                                            <a href="{{ route('hap.review.researchcopyright.show', $submission->form_id) }}">
                                                                C.6.  Copyrighted Research Output
                                                            </a>
                                                        @break 
                                                        @case('invention')
                                                            <a href="{{ route('hap.review.invention.show', $submission->form_id) }}">
                                                                D.1. Faculty Invention, Innovation and Creative Works Commitment
                                                            </a>
                                                        @break 
                                                        @case('expertconsultant')
                                                            <a href="{{ route('hap.review.expertconsultant.show', $submission->form_id) }}">
                                                                E.1. Expert Service Rendered - as a Consultant/Expert
                                                            </a>
                                                        @break 
                                                        @case('expertconference')
                                                            <a href="{{ route('hap.review.expertconference.show', $submission->form_id) }}">
                                                                E.1. Expert Service Rendered - in Conferences
                                                            </a>
                                                        @break 
                                                        @case('expertjournal')
                                                            <a href="{{ route('hap.review.expertjournal.show', $submission->form_id) }}">
                                                                E.1. Expert Service Rendered - in Academic Journals
                                                            </a>
                                                        @break 
                                                        @case('extensionprogram')
                                                            <a href="{{ route('hap.review.extensionprogram.show', $submission->form_id) }}">
                                                                E.2. Extension Program, Project and Activity (Ongoing and Completed)
                                                            </a>
                                                        @break 
                                                        @case('partnership')
                                                        <a href="{{ route('hap.review.partnership.show', $submission->form_id) }}">
                                                                E.3. Partnership/Linkages/Network
                                                        </a>
                                                        @break 
                                                        @case('facultyintercountry')
                                                            <a href="{{ route('hap.review.facultyintercountry.show', $submission->form_id) }}">
                                                                E.4. Faculty Involvement in Inter-Country Mobility
                                                            </a>
                                                        @break 
                                                        @case('material')
                                                            <a href="{{ route('hap.review.material.show', $submission->form_id) }}">
                                                                F.1. Instructional Material, Reference/Text Book, Module, Monographs
                                                            </a>
                                                        @break 
                                                        @case('syllabus')
                                                            <a href="{{ route('hap.review.syllabus.show', $submission->form_id) }}">
                                                                F.2. Course Syllabus/Guide Developed/Revised/Enhanced
                                                            </a>
                                                        @break 
                                                        @case('specialtask')
                                                            <a href="{{ route('hap.review.specialtask.show', $submission->form_id) }}">
                                                                III. Special Tasks
                                                            </a>
                                                        @break 
                                                        @case('specialtaskefficiency')
                                                            <a href="{{ route('hap.review.specialtaskefficiency.show', $submission->form_id) }}">
                                                                III. Special Tasks - Commitment Measurable by Efficiency
                                                            </a>
                                                        @break 
                                                        @case('specialtasktimeliness')
                                                            <a href="{{ route('hap.review.specialtasktimeliness.show', $submission->form_id) }}">
                                                                III. Special Tasks - Commitment Measurable by Timeliness
                                                            </a>
                                                        @break 
                                                        @case('attendancefunction')
                                                            <a href="{{ route('hap.review.attendancefunction.show', $submission->form_id) }}">
                                                                IV. Attendance in University Function
                                                            </a>
                                                        @break 
                                                        @case('viableproject')
                                                            <a href="{{ route('hap.review.viableproject.show', $submission->form_id) }}">
                                                                V. Viable Demonstration Projects
                                                            </a>
                                                        @break 
                                                        @case('branchaward')
                                                            <a href="{{ route('hap.review.branchaward.show', $submission->form_id) }}">
                                                                VI.  Awards/Recognitions Received by College/Branch/Campus from Reputable Organizations																								
                                                            </a>
                                                        @break 
                                                        @default
                                                    @endswitch
                                                </td>
                                                <td>{{ $submission->last_name.', '.$submission->first_name.' '.$submission->middle_name }}</td>
                                                <td>{{ date("M j, Y, g:i a" , strtotime($submission->created_at)) }}</td>
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
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready( function () {
            $('#review_table').DataTable({
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