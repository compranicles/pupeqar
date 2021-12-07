<x-app-layout>
    <x-slot name="header">
        <h2 class="h4 font-weight-bold">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <p style="font-size: 20px; font-weight: 600 !important; color: gray;">INDIVIDUAL QUARTERLY ACCOMPLISHMENTS</p>
    

            <div class="row">
                <div class="col-md-12">
                    <div class="row justify-content-center">
                        <div class="d-flex align-content-stretch flex-wrap db-card-group ml-3">
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Accomplishment Based on IPCR</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Attendance in University Function</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Awards and Recognition</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>  
                                </div>
                            </div>
                            @chairperson
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Awards and Recognition Received<br>by the Department</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @endchairperson
                            @director
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Awards and Recognition Received</a>by the College</h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @enddirector
                            @can('viewAny', \App\Models\OutreachProgram::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Community Relation and Outreach Program</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            @can('viewAny', \App\Models\Syllabus::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Course Syllabus</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            @can('viewAny', \App\Models\ExpertServiceConsultant::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Expert Service as Consultant</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            @can('viewAny', \App\Models\ExpertServiceAcademic::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="card-title"><a href="">Expert Service in Academic Journals, Books, Publications, Newsletter, & Creative Works</a> </h6>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div> 
                                </div>
                            </div>
                            @endcan
                            @can('viewAny', \App\Models\ExpertServiceConference::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="card-title"><a href="">Expert Service in Conference, Workshops, & Training Courses </a></h6>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            @can('viewAny', \App\Models\ExtensionService::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Extension Services</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            @can('viewAny', \App\Models\Mobility::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Inter-country Mobility</a> </h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            @can('viewAny', \App\Models\Invention::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="card-title"><a href="">Invention, Innovation, & Creative Works</a></h6>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                     
                                </div>
                            </div>
                            @endcan
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Officership/Membership</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                     
                                </div>
                            </div>
                            @can('viewAny', \App\Models\Partnership::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Partnership, Linkages, & Network </a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Professional Study</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @can('viewAny', \App\Models\Reference::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="card-title"><a href="">Reference, Textbook, Module, Monographs, & Instructional Materials </a></h6>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            @can('viewAny', \App\Models\Research::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Research</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                </div>
                            </div>
                            @endcan
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Seminars</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                     
                                </div>
                            </div>
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Special Tasks</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                     
                                </div>
                            </div>
                            @can('viewAny', \App\Models\StudentTraining::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Student Attended Seminars and Trainings</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                     
                                </div>
                            </div>
                            @endcan
                            @can('viewAny', \App\Models\StudentAward::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Student Awards and Recognition</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                     
                                </div>
                            </div>
                            @endcan
                            @can('viewAny', \App\Models\TechnicalExtension::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Technical Extension Programs/Projects/Activities</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                     
                                </div>
                            </div>
                            @endcan
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Trainings</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                     
                                </div>
                            </div>
                            @can('viewAny', \App\Models\ViableProject::class)
                            <div class="card bg-light text-dark db-card">
                                <img class="card-img"  src="..." alt="Card image">
                                <div class="card-img-overlay db-overlay">
                                    <div class="d-flex justify-content-between">
                                        <h5 class="card-title"><a href="">Viable Demonstration Projects</a></h5>
                                        <h2 class="number_of_entry" style="font-weight: bold">9</h2>
                                    </div>
                                     
                                </div>
                            </div> 
                            @endcan    
                        </div>
                                        
                    </div>
                </div>
            </div>

    
</x-app-layout>
