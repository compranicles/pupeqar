<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    User,
    Role,
    Research,
    ResearchComplete,
    ResearchPresentation,
    ResearchPublication,
    ResearchCopyright,
    ResearchCitation,
    ResearchUtilization,
    Invention,
    ExpertServiceAcademic,
    ExpertServiceConference,
    ExpertServiceConsultant,
    ExtensionService,
    Mobility,
    OutreachProgram,
    Partnership,
    CollegeDepartmentAward,
    Reference,
    StudentAward,
    StudentTraining,
    Syllabus,
    TechnicalExtension,
    ViableProject,
    Request,

    Announcement,
};
use App\Models\Maintenance\{
    College,
    Department,
    Currency,
    Sector,
    HRISForm,
    GenerateType,
    ReportType,
};
use App\Models\FormBuilder\{
    Dropdown,
    ResearchForm,
    InventionForm,
    ExtensionProgramForm,
    AcademicDevelopmentForm,
    IPCRForm
};

use App\Models\Authentication\Permission;
use App\Policies\Authentication\{
    RolePolicy,
    PermissionPolicy,
    UserPolicy,
};
use App\Policies\Research\{
    ResearchPolicy,
    ResearchCompletionPolicy,
    ResearchPresentationPolicy,
    ResearchPublicationPolicy,
    ResearchCopyrightPolicy,
    ResearchUtilizationPolicy,
    ResearchCitationPolicy,
};
use App\Policies\Invention\InventionPolicy;
use App\Policies\ExtensionProgram\ExpertService\AcademicPolicy;
use App\Policies\ExtensionProgram\ExpertService\ConferencePolicy;
use App\Policies\ExtensionProgram\ExpertService\ConsultantPolicy;
use App\Policies\ExtensionProgram\ExtensionServicePolicy;
use App\Policies\ExtensionProgram\MobilityPolicy;
use App\Policies\ExtensionProgram\OutreachProgramPolicy;
use App\Policies\ExtensionProgram\PartnershipPolicy;
use App\Policies\AcademicDevelopment\CollegeDepartmentAwardPolicy;
use App\Policies\AcademicDevelopment\ReferencePolicy;
use App\Policies\AcademicDevelopment\StudentAwardPolicy;
use App\Policies\AcademicDevelopment\StudentTrainingPolicy;
use App\Policies\AcademicDevelopment\SyllabusPolicy;
use App\Policies\AcademicDevelopment\TechnicalExtensionPolicy;
use App\Policies\AcademicDevelopment\ViableProjectPolicy;

use App\Policies\Content\AnnouncementPolicy;
use App\Policies\Maintenance\{
    CollegePolicy,
    HRISFormPolicy,
    IPCRFormPolicy,
    AcademicModuleFormPolicy,
    ReportTypePolicy,
    ReportGenerateTypePolicy,
};
use App\Policies\Maintenance\DepartmentPolicy;
use App\Policies\Maintenance\CurrencyPolicy;
use App\Policies\Maintenance\DropdownPolicy;
use App\Policies\Maintenance\SectorPolicy;
use App\Policies\Maintenance\Research\ResearchFormPolicy;
use App\Policies\Maintenance\Invention\InventionFormPolicy;
use App\Policies\Maintenance\ExtensionProgram\ExtensionProgramFormPolicy;
use App\Policies\Request\RequestPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',

        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        User::class => UserPolicy::class,

        //Maintenances
        College::class => CollegePolicy::class,
        Department::class => DepartmentPolicy::class,
        Currency::class => CurrencyPolicy::class,
        Dropdown::class => DropdownPolicy::class,
        Sector::class => SectorPolicy::class,
        GenerateType::class => ReportGenerateTypePolicy::class,
        ReportType::class => ReportTypePolicy::class,

        //Content Management
        Announcement::class => AnnouncementPolicy::class,
        
        //Form Management
        ResearchForm::class => ResearchFormPolicy::class,
        InventionForm::class => InventionFormPolicy::class,
        ExtensionProgramForm::class => ExtensionProgramFormPolicy::class,
        HRISForm::class => HRISFormPolicy::class,
        IPCRForm::class => IPCRFormPolicy::class,
        AcademicDevelopmentForm::class => AcademicModuleFormPolicy::class,

        //Faculty Research
        Research::class => ResearchPolicy::class,
        ResearchComplete::class => ResearchCompletionPolicy::class,
        ResearchPresentation::class => ResearchPresentationPolicy::class,
        ResearchPublication::class => ResearchPublicationPolicy::class,
        ResearchCitation::class => ResearchCitationPolicy::class,
        ResearchCopyright::class => ResearchCopyrightPolicy::class,
        ResearchUtilization::class => ResearchUtilizationPolicy::class,

        //Faculty Invention
        Invention::class => InventionPolicy::class,

        //Extension Programs and Services
        //Expert Services
        ExpertServiceAcademic::class => AcademicPolicy::class,
        ExpertServiceConference::class => ConferencePolicy::class,
        ExpertServiceConsultant::class => ConsultantPolicy::class,
        //Extension Programs
        ExtensionService::class => ExtensionServicePolicy::class,
        Mobility::class => MobilityPolicy::class,
        OutreachProgram::class => OutreachProgramPolicy::class,
        Partnership::class => PartnershipPolicy::class,

        //Academic Development
        CollegeDepartmentAward::class => CollegeDepartmentAwardPolicy::class,
        Reference::class => ReferencePolicy::class,
        StudentAward::class => StudentAwardPolicy::class,
        StudentTraining::class => StudentTrainingPolicy::class,
        Syllabus::class => SyllabusPolicy::class,
        TechnicalExtension::class => TechnicalExtensionPolicy::class,
        ViableProject::class => ViableProjectPolicy::class,

        Request::class => RequestPolicy::class,

        //For authorization of reports (to receive and consolidation), please refer to Services (App/Services)
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
    }

}
