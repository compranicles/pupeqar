<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Reports') }}
        </h2>
    </x-slot>

    <div class="container">
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-md-1 h5 mt-1 font-weight-bold text-md-right">
                                Filters:
                            </div>
                        </div>
                        {{-- Filters --}}
                        <form action="" method="POST">
                            @csrf
                            <div class="row justify-content-end">
                                {{-- Quarter Filter --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="quarter" id="quarter" class="form-control form-control-sm" placeholder="Quarter">
                                            <option value="" selected disabled>Quarter</option>
                                            <option value="1">January - March</option>
                                            <option value="2">April - June</option>
                                            <option value="3">July - September</option>
                                            <option value="4">October - December</option>
                                            <option value="5">Whole Year</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Date Filter --}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <select name="year" id="year" class="form-control form-control-sm">
                                            <option value="" selected disabled>Year</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Author Filter --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select name="author" id="author" class="form-control form-control-sm">
                                            <option value="" selected disabled>Author</option>
                                            <option value="all">All</option>
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->last_name.', '.$user->first_name.' '.$user->middle_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- Form Filter --}}
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select name="form" id="form" class="form-control form-control-sm">
                                            <option value="" selected disabled>Accomplishment</option>
                                            <option value="all">All</option>
                                            <option value="ongoingadvanced">
                                                A. Ongoing Advanced/Professional Study
                                            </option>   
                                            <option value="facultyaward">
                                                B.1. Faculty Outstanding Achievements/Awards
                                            </option>
                                            <option value="officership">
                                                B.2. Officership/Membership in Professional Organization/s
                                            </option>   
                                            <option value="attendanceconference">
                                                B.3.1 Attendance in Relevant Faculty Development Program (Seminars/Webinars, Fora/Conferences) 
                                            </option>   
                                            <option value="attendancetraining">
                                                B.3.2. Attendance in Training/s
                                            </option>   
                                            <option value="research">
                                                C.1. Research Ongoing/Completed
                                            </option>   
                                            <option value="researchpublication">
                                                C.2. Research Publication
                                            </option>   
                                            <option value="researchpresentation">
                                                C.3. Research Presentation
                                            </option>   
                                            <option value="researchcitation">
                                                C.4. Research Citation
                                            </option>  
                                            <option value="researchutilization">
                                                C.5. Research Utilization
                                            </option>  
                                            <option value="researchcopyright">
                                                C.6.  Copyrighted Research Output
                                            </option>  
                                            <option value="invention">
                                                D.1. Faculty Invention, Innovation and Creative Works Commitment
                                            </option>  
                                            <option value="expertconsultant">
                                                E.1. Expert Service Rendered - as a Consultant/Expert
                                            </option>
                                            <option value="expertconference">
                                                E.1. Expert Service Rendered - in Conferences
                                            </option>  
                                            <option value="expertjournal">
                                                E.1. Expert Service Rendered - in Academic Journals
                                            </option>  
                                            <option value="extensionprogram">
                                                E.2. Extension Program, Project and Activity (Ongoing and Completed)
                                            </option>  
                                            <option value="partnership">
                                                E.3. Partnership/Linkages/Network
                                            </option>  
                                            <option value="facultyintercountry">
                                                E.4. Faculty Involvement in Inter-Country Mobility
                                            </option>  
                                            <option value="material">
                                                F.1.  Material, Reference/Text Book, Module, Monographs
                                            </option>
                                            <option value="syllabus">
                                                F.2. Course Syllabus/Guide Developed/Revised/Enhanced
                                            </option>  
                                            <option value="specialtask">
                                                III. Special Tasks
                                            </option>  
                                            <option value="specialtaskefficiency">
                                                III. Special Tasks - Commitment Measurable by Efficiency
                                            </option>  
                                            <option value="specialtasktimeliness">
                                                III. Special Tasks - Commitment Measurable by Timeliness
                                            </option>  
                                            <option value="attendancefunction">
                                                IV. Attendance in University Function
                                            </option>  
                                            <option value="viableproject">
                                                V. Viable Demonstration Projects
                                            </option>
                                            <option value="branchaward">
                                                VI.  Awards/Recognitions Received by College/Branch/Campus from Reputable Organizations																								
                                            </option>  
                                            <option value="studentaward">
                                                VII. Students Awards/Recognitions from  Reputable Organizations
                                            </option>
                                            <option value="outreachprogram">
                                                VIII. Community Relation and Outreach Program
                                            </option>
                                            <option value="studenttraining">
                                                IX. Student Trainings and Seminars
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                {{-- Submit Button --}}
                                <div class="col-md-1">
                                    <div class="d-flex flex-column">
                                        <button type="submit" class="btn btn-sm btn-dark pt-1 pb-1 mt-1">Filter</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        {{-- A Tables --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="card border border-maroon">
                                    <div class="card-header">
                                        <h6 id="textHome" class="text-maroon font-weight-bold mt-1 mb-n1">A. ONGOING ADVANCED/ PROFESSIONAL STUDY</h6>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="table-responsive">
                                            <table class="table table-sm table-bordered">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th colspan="4"></th>
                                                        <th colspan="2">School</th>
                                                        <th colspan="3">Means of Educational Support</th>
                                                        <th colspan="2">Duration</th>
                                                        <th colspan="4"></th>
                                                    </tr>
                                                    <tr>
                                                        <th scope="col">#</th>
                                                        <th scope="col">Department</th>
                                                        <th scope="col">Name of the Employee</th>
                                                        <th scope="col">Degree/ Program</th>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Program Accreditation Level/ World Ranking/ COE or COD</th>
                                                        <th scope="col">Type of Support</th>
                                                        <th scope="col">Name of Sponsor/ Agency/ Organization</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">From</th>
                                                        <th scope="col">To</th>
                                                        <th scope="col">Status</th>
                                                        <th scope="col">Number of Units Earned</th>
                                                        <th scope="col">Number of Units Currently Enrolled</th>
                                                        <th scope="col">Description of Supporting Documents</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($ongoingstudies as  $row)
                                                    <tr>
                                                        <th scope="col">{{ $loop->iteration }}</th>
                                                        <td></td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        {{-- B Tables --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="card border border-maroon">
                                    <div class="card-header">
                                        <h6 id="textHome" class="text-maroon font-weight-bold mt-1 mb-n1">B. OUTSTANDING ACHIEVEMENTS/ AWARDS, OFFICERSHIP/ MEMBERSHIP IN PROFESSIONAL ORGANIZATION/S, &#38; TRAININGS/ SEMINARS ATTENDED </h6>
                                    </div>
                                    <div class="card-body">
                                        {{-- Faculty Award --}} 
                                        <div class="card border border-maroon mb-3">
                                            <div class="card-header">
                                                <p id="textHome" class="text-maroon font-weight-bold m-n1">B.1. Faculty Outstanding Achievements/Awards </p>
                                            </div>
                                            <div class="card-body">
                    
                                            </div>
                                        </div>
                                        {{-- Officership --}}
                                        <div class="card border border-maroon mb-3">
                                            <div class="card-header">
                                                <p id="textHome" class="text-maroon font-weight-bold m-n1">B.2. Officership/Membership in Professional Organization/s </p>
                                            </div>
                                            <div class="card-body">
                                                
                                            </div>
                                        </div>
                                        {{-- Attendance Conference --}}
                                        <div class="card border border-maroon mb-3">
                                            <div class="card-header">
                                                <p id="textHome" class="text-maroon font-weight-bold m-n1">B.3.1 Attendance in Relevant Faculty Development Program (Seminars/Webinars, Fora/Conferences)</p>																			
                                            </div>
                                            <div class="card-body">
                                                
                                            </div>
                                        </div>
                                        {{-- Attendance Training --}}
                                        <div class="card border border-maroon mb-3">
                                            <div class="card-header">
                                                <p id="textHome" class="text-maroon font-weight-bold m-n1">B.3.2. Attendance in Training/s </p>																			
                                            </div>
                                            <div class="card-body">
                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    @push('scripts')
    <script type="text/javascript" src="{{ asset('dist/selectize.js') }}"></script>
    <script type="text/javascript" src="{{ asset('dist/selectize.min.js') }}"></script>

    <script>
        $(document).ready( function () {
            $("#author").selectize({
                sortField: "text",
            });
            $("#quarter").selectize();

            for (i = new Date().getFullYear(); i > 2000; i--)
            {
                $('#year').append($('<option />').val(i).html(i));
            }
            $("#year").selectize();
            $('#form').selectize();
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