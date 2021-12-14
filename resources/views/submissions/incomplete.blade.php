<x-app-layout>

    <div class="container">
        
        <nav class="nav">
            <a class="nav-link submission_links {{ request()->routeIs('submissions.incomplete') ? 'active' : ''}}" aria-current="page" href="{{ route('submissions.incomplete') }}">Incomplete</a>
            <a class="nav-link submission_links" href="#">Denied</a>
        </nav>
        <br>
        @if ($researches->isEmpty() && $inventions->isEmpty() && $syllabi->isEmpty() && $allRtmmi->isEmpty() && $student_awards->isEmpty() &&
            $student_trainings->isEmpty() && $viable_projects->isEmpty() &&
            $college_department_awards->isEmpty() && $technical_extensions->isEmpty() &&
            $expertServicesConsultant->isEmpty() && $expertServicesConference->isEmpty() && $expertServicesAcademic->isEmpty() &&
            $extensionServices->isEmpty() && $partnerships->isEmpty() &&
            $mobilities->isEmpty() && $outreach_programs->isEmpty())

            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-success text-center p-5" role="alert">
                        You have submitted all the accomplishments with supporting documents.
                    </div>
                </div>
            </div>
        @else
        <div class="card mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav justify-content-center m-n3">
                            <!-- <li class="nav-item">
                                <x-jet-nav-link href="" class="text-dark"  class="text-dark">
                                    {{ __('Personnel') }}
                                </x-jet-nav-link>
                            </li>
                            <li class="nav-item">
                                <x-jet-nav-link href="" class="text-dark">
                                    {{ __('Employee Development') }}
                                </x-jet-nav-link>
                            </li> -->
                            <!-- <li class="nav-item">
                                <x-jet-nav-link href="" class="text-dark">
                                    {{ __('Request & Queries') }}
                                </x-jet-nav-link>
                            </li> -->
                            <!-- <li class="nav-item">
                                <x-jet-nav-link href="" class="text-dark" >
                                    {{ __('IPCR') }}
                                </x-jet-nav-link>
                            </li> -->
                            @if(! $researches->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#research" class="text-dark">
                                    {{ __('Research & Book Chapter') }} <span class="badge bg-danger">{{ count($researches) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $inventions->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#invention" class="text-dark">
                                    {{ __('Invention, Innovation, & Creativity') }} <span class="badge bg-danger">{{ count($inventions) }}</span>

                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $syllabi->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#syllabus" class="text-dark">
                                    {{ __('Course Syllabus') }} <span class="badge bg-danger">{{ count($syllabi) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $allRtmmi->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#rtmmi" class="text-dark">
                                    {{ __('Reference, Textbooks, Module, Monographs, & Instructional Materials') }} <span class="badge bg-danger">{{ count($allRtmmi) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $student_awards->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#student-awards" class="text-dark">
                                    {{ __('Student Awards and Recognition') }} <span class="badge bg-danger">{{ count($student_awards) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $student_trainings->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#student-trainings" class="text-dark">
                                    {{ __('Student Attended Seminars and Trainings') }} <span class="badge bg-danger">{{ count($student_trainings) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $viable_projects->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#viable-project" class="text-dark">
                                    {{ __('Viable Demonstration Projects') }} <span class="badge bg-danger">{{ count($viable_projects) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $college_department_awards->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#college-awards" class="text-dark">
                                    {{ __('Awards and Recognition Received by the College') }} <span class="badge bg-danger">{{ count($college_department_awards) }}</span>
                                    <!-- {{ __('Awards and Recognition Received by the Department') }} -->
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $technical_extensions->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#technical-extensions" class="text-dark">
                                    {{ __('Technical Extension Programs, Projects, & Activities') }} <span class="badge bg-danger">{{ count($technical_extensions) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $expertServicesConsultant->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#expert-service-consultant" class="text-dark">
                                    {{ __('Expert Service Rendered as Consultant') }} <span class="badge bg-danger">{{ count($expertServiceConsultant) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $expertServicesConference->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#expert-service-conference" class="text-dark">
                                    {{ __('Expert Service Rendered in Conference, Workshops, and/or Training Course for Professional') }} <span class="badge bg-danger">{{ count($expertServiceConference) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $expertServicesAcademic->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#expert-service-academic" class="text-dark">
                                    {{ __('Expert Service Rendered in Academic Journals, Books, Publication, Newsletter, & Creative Works') }} <span class="badge bg-danger">{{ count($expertServiceAcademic) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $extensionServices->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#extension-services" class="text-dark">
                                    {{ __('Extension Services') }} <span class="badge bg-danger">{{ count($extensionServices) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $partnerships->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="#partnerships" class="text-dark">
                                    {{ __('Partnership, Linkages, & Network') }} <span class="badge bg-danger">{{ count($partnerships) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @if(! $mobilities->isEmpty())
                            @endif
                            <li class="nav-item">
                                <x-jet-nav-link href="inter-country-mobility" class="text-dark">
                                    {{ __('Inter-country Mobility') }} <span class="badge bg-danger">{{ count($mobilities) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                            @if(! $outreach_programs->isEmpty())
                            <li class="nav-item">
                                <x-jet-nav-link href="outreach" class="text-dark">
                                    {{ __('Community Relation and Outreach Program') }} <span class="badge bg-danger">{{ count($outreach_programs) }}</span>
                                </x-jet-nav-link>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @if(! $researches->isEmpty())
        <br>
        <h3 id="research" class="submission-categories jump-target">Research & Book Chapter</h3>
        <div class="card h-100">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            
                            <table class="table" id="researchTable" >
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Research Code</th>
                                        <th>Research Title</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($researches as $research)
                                        <tr role="button">
                                            <td><a href="{{ route('research.show', $research->id) }}" class="link text-dark">{{ $loop->iteration }}</a></td>
                                            <td>{{ $research->research_code }}</td>
                                            <td>{{ $research->title }}</td>
                                            <td>{{ $research->status_name }}</td>
                                            <td>
                                            <div role="group">
                                                <a href="{{ route('rtmmi.edit', $rtmmi->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                            </div>
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
        @endif
        @if(! $inventions->isEmpty())
        <br>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <h3 id="invention" class="submission-categories jumptarget">Inventions, Innovation, & Creativity</h3>
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table" id="invention_table">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Date Modified</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($inventions as $invention)
                                    <tr class="tr-hover" role="button">
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' " >{{ $loop->iteration }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' " >{{ $invention->title }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' " >{{ $invention->status_name }}</td>
                                        <td onclick="window.location.href = '{{ route('invention-innovation-creative.show', $invention->id) }}' " >{{ $invention->updated_at }}</td>
                                        <td>
                                            <div role="group">
                                                <a href="{{ route('invention-innovation-creative.edit', $invention->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                            </div>
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
        @endif
        @if(! $syllabi->isEmpty() || ! $allRtmmi->isEmpty() || ! $student_awards->isEmpty() ||
            ! $student_trainings->isEmpty() || ! $viable_projects->isEmpty() ||
            ! $college_department_awards->isEmpty() || ! $technical_extensions->isEmpty())
        <br>
        <hr>
        <h3 class="submission-categories">Academic Program Development</h3>
        @endif
        @if(! $syllabi->isEmpty())
        <h4 id="syllabus" class="jumptarget mt-3">Course Syllabus</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="syllabus_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Course Title</th>
                                <th>Assigned Task</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($syllabi as $syllabus)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >{{ $syllabus->course_title }}</td>
                                <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >{{ $syllabus->assigned_task_name }}</td>
                                <td onclick="window.location.href = '{{ route('syllabus.show', $syllabus->id) }}' " >{{ $syllabus->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('syllabus.edit', $syllabus->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $allRtmmi->isEmpty())
        <br>
        <h4  id="rtmmi" class="jumptarget mt-3">Reference, Textbooks, Module, Monographs, & Instructional Materials</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="rtmmi_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allRtmmi as $rtmmi)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >{{ $rtmmi->title }}</td>
                                <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >{{ $rtmmi->category_name }}</td>
                                <td onclick="window.location.href = '{{ route('rtmmi.show', $rtmmi->id) }}' " >{{ $rtmmi->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('rtmmi.edit', $rtmmi->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $rtmmi->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-rtmmi="{{ $rtmmi->title }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $student_awards->isEmpty())
        <br>
        <h4 id="student-awards" class="jumptarget mt-3">Student Awards & Recognition</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="student_award_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name of Award</th>
                                <th>Certifying Body</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student_awards as $row)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('student-award.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('student-award.show', $row->id) }}' " >{{ $row->name_of_award }}</td>
                                <td onclick="window.location.href = '{{ route('student-award.show', $row->id) }}' " >{{ $row->certifying_body }}</td>
                                <td onclick="window.location.href = '{{ route('student-award.show', $row->id) }}' " >{{ $row->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('student-award.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-student="{{ $row->name_of_award }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $student_trainings->isEmpty())
        <br>
        <h4 id="student-trainings" class="jumptarget mt-3">Student Attended Seminars & Trainings</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="student_training_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name of Student</th>
                                <th>Title</th>
                                <th>Organization</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($student_trainings as $row)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >{{ $row->name_of_student }}</td>
                                <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >{{ $row->title }}</td>
                                <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >{{ $row->organization }}</td>
                                <td onclick="window.location.href = '{{ route('student-training.show', $row->id) }}' " >{{ $row->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('student-training.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-student="{{ $row->title }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $viable_projects->isEmpty())
        <br>
        <h4 id="viable-projects" class="jumptarget mt-3">Viable Demonstration Projects</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="project_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name of Viable Demonstration Project</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($viable_projects as $row)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('viable-project.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('viable-project.show', $row->id) }}' " >{{ $row->name }}</td>
                                <td onclick="window.location.href = '{{ route('viable-project.show', $row->id) }}' " >{{ $row->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('viable-project.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-project="{{ $row->name }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $college_department_awards->isEmpty())
        <br>
        <h4 id="college-awards" class="jumptarget mt-3">Awards and Recognition Received by the College</h4>
        <!-- <h4 id="department-awards" class="jumptarget mt-3">Awards and Recognition Received by the College</h4> -->
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="college_department_award_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name of Award</th>
                                <th>Certifying Body</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($college_department_awards as $row)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('college-department-award.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('college-department-award.show', $row->id) }}' " >{{ $row->name_of_award }}</td>
                                <td onclick="window.location.href = '{{ route('college-department-award.show', $row->id) }}' " >{{ $row->certifying_body }}</td>
                                <td onclick="window.location.href = '{{ route('college-department-award.show', $row->id) }}' " >{{ $row->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('college-department-award.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-award="{{ $row->name_of_award }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $technical_extensions->isEmpty())
        <br>
        <h4 id="technical-extensions" class="jumptarget mt-3">Technical Extension Programs, Projects, & Activities</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="technical_extension_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Name of Adoptor</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($technical_extensions as $row)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('technical-extension.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('technical-extension.show', $row->id) }}' " >{{ $row->name_of_adoptor }}</td>
                                <td onclick="window.location.href = '{{ route('technical-extension.show', $row->id) }}' " >{{ $row->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('technical-extension.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-extension="{{ $row->name_of_adoptor }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $expertServicesConsultant->isEmpty() || ! $expertServicesConference->isEmpty() || ! $expertServicesAcademic->isEmpty() ||
            ! $extensionServices->isEmpty() || ! $partnerships->isEmpty() ||
            ! $mobilities->isEmpty() || ! $outreach_programs->isEmpty())
        <br>
        <hr>
        <h3 class="submission-categories">Extension Programs & Expert Services</h3>
        @endif
        @if(! $expertServicesConsultant->isEmpty())
        <h4 id="expert-service-consultant" class="jumptarget mt-3">Expert Service Rendered as Consultant</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="esconsultant_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Classification</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expertServicesConsultant as $expertServiceConsultant)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' " >{{ $expertServiceConsultant->title }}</td>
                                <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' " >{{ $expertServiceConsultant->classification_name }}</td>
                                <td onclick="window.location.href = '{{ route('expert-service-as-consultant.show', $expertServiceConsultant->id) }}' " >{{ $expertServiceConsultant->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('expert-service-as-consultant.edit', $expertServiceConsultant) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $expertServiceConsultant->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-esconsultant="{{ $expertServiceConsultant->title }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $expertServicesConference->isEmpty())
        <br>
        <h4 id="expert-service-conference" class="jumptarget mt-3">Expert Service Rendered in Conference, Workshops, and/or Training Course for Professional</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="esconference_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title</th>
                                <th>Nature</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expertServicesConference as $expertServiceConference)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' " >{{ $expertServiceConference->title }}</td>
                                <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' " >{{ $expertServiceConference->nature }}</td>
                                <td onclick="window.location.href = '{{ route('expert-service-in-conference.show', $expertServiceConference->id) }}' " >{{ $expertServiceConference->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('expert-service-in-conference.edit', $expertServiceConference) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $expertServiceConference->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-esconference="{{ $expertServiceConference->title }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $expertServicesAcademic->isEmpty())
        <br>
        <h4 id="expert-service-academic" class="jumptarget mt-3">Expert Service Rendered in Academic Journals, Books, Publication, Newsletter, & Creative Works</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="esacademic_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Publication/ Audio Visual Production</th>
                                <th>Classification</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expertServicesAcademic as $expertServiceAcademic)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' " >{{ $expertServiceAcademic->publication_or_audio_visual }}</td>
                                <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' " >{{ $expertServiceAcademic->classification }}</td>
                                <td onclick="window.location.href = '{{ route('expert-service-in-academic.show', $expertServiceAcademic->id) }}' " >{{ $expertServiceAcademic->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('expert-service-in-academic.edit', $expertServiceAcademic) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $expertServiceAcademic->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-esacademic="{{ $expertServiceAcademic->title }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $extensionServices->isEmpty())
        <br>
        <h4 id="extension-services" class="jumptarget mt-3">Extension Services</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="eservice_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title of Extension Program</th>
                                <th>Status</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($extensionServices as $extensionService)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' " >{{ $extensionService->title_of_extension_program }}</td>
                                <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' " >{{ $extensionService->status }}</td>
                                <td onclick="window.location.href = '{{ route('extension-service.show', $extensionService->id) }}' " >{{ $extensionService->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('extension-service.edit', $extensionService) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $extensionService->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-eservice="{{ $extensionService->title_of_extension_program }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $partnerships->isEmpty())
        <br>
        <h4 id="partnerships" class="jumptarget mt-3">Partnership, Linkages, & Network</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="partnership_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>MOA/MOU Code</th>
                                <th>Title</th>
                                <th>Name of Organization/ Partner</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($partnerships as $row)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' " >{{ $row->moa_code }}</td>
                                <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' " >{{ $row->title_of_partnership }}</td>
                                <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' " >{{ $row->name_of_partner }}</td>
                                <td onclick="window.location.href = '{{ route('partnership.show', $row->id) }}' " >{{ $row->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('partnership.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-partnership="{{ $row->title_of_partnership }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $mobilities->isEmpty())
        <br>
        <h4 id="inter-country-mobility" class="jumptarget mt-3">Inter-country Mobility</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="mobility_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Hosting Institution/ Organization/ Agency</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mobilities as $row)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' " >{{ $row->host_name }}</td>
                                <td onclick="window.location.href = '{{ route('mobility.show', $row->id) }}' " >{{ $row->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('mobility.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-mobility="{{ $row->host_name }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
        @if(! $outreach_programs->isEmpty())
        <br>
        <h4 id="outreach" class="jumptarget mt-3">Community Relation and Outreach Program</h4>
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="outreach_table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Title of the Program</th>
                                <th>Date Modified</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($outreach_programs as $row)
                            <tr class="tr-hover" role="button">
                                <td onclick="window.location.href = '{{ route('outreach-program.show', $row->id) }}' " >{{ $loop->iteration }}</td>
                                <td onclick="window.location.href = '{{ route('outreach-program.show', $row->id) }}' " >{{ $row->title_of_the_program }}</td>
                                <td onclick="window.location.href = '{{ route('outreach-program.show', $row->id) }}' " >{{ $row->updated_at }}</td>
                                <td>
                                    <div role="group">
                                        <a href="{{ route('outreach-program.edit', $row->id) }}"  class="action-edit mr-3"><i class="bi bi-pencil-square"></i> Edit</a>
                                        <button type="button" value="{{ $row->id }}" class="action-delete" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-outreach="{{ $row->title_of_the_program }}"><i class="bi bi-trash"></i> Delete</button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    @push('scripts')
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.1/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
             $('table').DataTable();
        });

    </script>
    @endpush
</x-app-layout>