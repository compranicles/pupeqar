<select id="reportFilter" class="custom-select" name="report">
    <option value="">Show All</option>
    @can('viewAny', App\Models\Research::class)
    <option value="Research Registration" class="accomplishment">Research Registration</option>
    @endcan
    @can('viewAny', App\Models\ResearchComplete::class)
    <option value="Research Completed" class="accomplishment">Research Completed</option>
    @endcan
    @can('viewAny', App\Models\ResearchPublication::class)
    <option value="Research Publication" class="accomplishment">Research Publication</option>
    @endcan
    @can('viewAny', App\Models\ResearchPresentation::class)
    <option value="Research Presentation" class="accomplishment">Research Presentation</option>
    @endcan
    @can('viewAny', App\Models\ResearchCitation::class)
    <option value="Research Citation" class="accomplishment">Research Citation</option>
    @endcan
    @can('viewAny', App\Models\ResearchUtilization::class)
    <option value="Research Utilization" class="accomplishment">Research Utilization</option>
    @endcan
    @can('viewAny', App\Models\ResearchCopyright::class)
    <option value="Copyrighted Research Output" class="accomplishment">Copyrighted Research Output</option>
    @endcan
    @can('viewAny', App\Models\Invention::class)
    <option value="Faculty Invention, Innovation, and Creative Works" class="accomplishment">Faculty Invention, Innovation, and Creative Works</option>
    @endcan
    @can('viewAny', App\Models\ExpertServiceConsultant::class)
    <option value="Expert Service Rendered as Consultant" class="accomplishment">Expert Service Rendered as Consultant</option>
    @endcan
    @can('viewAny', App\Models\ExpertServiceConference::class)
    <option value="Expert Service Rendered in Conference, Workshops, and/or training course for Professional" class="accomplishment">Expert Service Rendered in Conference, Workshops, and/or training course for Professional</option>
    @endcan
    @can('viewAny', App\Models\ExpertServiceAcademic::class)
    <option value="Expert Service Rendered in Academic Journals/Books/Publication/NewsLetter/Creative Works" class="accomplishment">Expert Service Rendered in Academic Journals/Books/Publication/NewsLetter/Creative Works</option>
    @endcan
    @can('viewAny', App\Models\ExtensionService::class)
    <option value="Expert Program, Project, and Active (Ongoing and Completed)" class="accomplishment">Expert Program, Project, and Active (Ongoing and Completed)</option>
    @endcan
    @can('viewAny', App\Models\Partnership::class)
    <option value="Partnership/Linkages/Network" class="accomplishment">Partnership/Linkages/Network</option>
    @endcan
    @can('viewAny', App\Models\Mobility::class)
    <option value="Faculty Involvement in Inter-Country Mobility" class="accomplishment">Faculty Involvement in Inter-Country Mobility</option>
    @endcan
    @can('viewAny', App\Models\Reference::class)
    <option value="Instructional Material, Reference/Text Book, Module, Monographs" class="accomplishment">Instructional Material, Reference/Text Book, Module, Monographs</option>
    @endcan
    @can('viewAny', App\Models\Syllabus::class)
    <option value="Course Syllabus/ Guide Developed/Revised/Enhanced" class="accomplishment">Course Syllabus/ Guide Developed/Revised/Enhanced</option>
    @endcan
    @can('viewAny', App\Models\Request::class)
    <option value="Request & Queries Acted Upon" class="accomplishment">Request & Queries Acted Upon</option>
    @endcan
    @can('viewAny', App\Models\StudentAward::class)
    <option value="Students Awards/ Recognitions from Reputable Organizations" class="accomplishment">Students Awards/ Recognitions from Reputable Organizations</option>
    @endcan
    @can('viewAny', App\Models\StudentTraining::class)
    <option value="Students Trainings and Seminars" class="accomplishment">Students Trainings and Seminars</option>
    @endcan
    @can('viewAny', App\Models\StudentAward::class)
    <option value="Viable Demonstration Project" class="accomplishment">Viable Demonstration Project</option>
    @endcan
    @can('viewAny', App\Models\CollegeDepartmentAward::class)
    <option value="Awards/ Recognitions Received by College/Branch/Campus from  Reputable Organizations" class="accomplishment">Awards/ Recognitions Received by College/Branch/Campus from  Reputable Organizations</option>
    @endcan
    @can('viewAny', App\Models\OutreachProgram::class)
    <option value="Community Relation and Outreach Program" class="accomplishment">Community Relation and Outreach Program</option>
    @endcan
    @can('viewAny', App\Models\TechnicalExtension::class)
    <option value="Technical Extension Program/Project/Activity" class="accomplishment">Technical Extension Program/Project/Activity</option>
    @endcan
</select>
