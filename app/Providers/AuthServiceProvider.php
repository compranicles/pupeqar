<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Role;
use App\Models\Research;
use App\Models\ResearchComplete;
use App\Models\ResearchPresentation;
use App\Models\ResearchPublication;
use App\Models\ResearchCopyright;
use App\Models\ResearchCitation;
use App\Models\ResearchUtilization;
use App\Models\Invention;
use App\Models\ExpertServiceAcademic;
use App\Models\ExpertServiceConference;
use App\Models\ExpertServiceConsultant;
use App\Models\ExtensionService;
use App\Models\Mobility;
use App\Models\OutreachProgram;
use App\Models\Partnership;
use App\Models\CollegeDepartmentAward;
use App\Models\Reference;
use App\Models\StudentAward;
use App\Models\StudentTraining;
use App\Models\Syllabus;
use App\Models\TechnicalExtension;
use App\Models\ViableProject;

use App\Models\Announcement;
use App\Models\Maintenance\College;
use App\Models\Maintenance\Department;
use App\Models\Maintenance\Currency;
use App\Models\FormBuilder\Dropdown;
use App\Models\FormBuilder\ResearchForm;
use App\Models\FormBuilder\InventionForm;
use App\Models\FormBuilder\ExtensionProgramForm;
use App\Models\FormBuilder\AcademicDevelopmentForm;

use App\Models\Authentication\Permission;
use App\Policies\Authentication\RolePolicy;
use App\Policies\Authentication\PermissionPolicy;
use App\Policies\Authentication\UserPolicy;
use App\Policies\Research\ResearchPolicy;
use App\Policies\Research\ResearchCompletionPolicy;
use App\Policies\Research\ResearchPresentationPolicy;
use App\Policies\Research\ResearchPublicationPolicy;
use App\Policies\Research\ResearchCopyrightPolicy;
use App\Policies\Research\ResearchUtilizationPolicy;
use App\Policies\Research\ResearchCitationPolicy;
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
use App\Policies\Maintenance\CollegePolicy;
use App\Policies\Maintenance\DepartmentPolicy;
use App\Policies\Maintenance\CurrencyPolicy;
use App\Policies\Maintenance\DropdownPolicy;
use App\Policies\Maintenance\Research\ResearchFormPolicy;
use App\Policies\Maintenance\Invention\InventionFormPolicy;
use App\Policies\Maintenance\ExtensionProgram\ExtensionProgramFormPolicy;


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

        //Content Management
        Announcement::class => AnnouncementPolicy::class,
        
        //Form Management
        ResearchForm::class => ResearchFormPolicy::class,
        InventionForm::class => InventionFormPolicy::class,
        ExtensionProgramForm::class => ExtensionProgramFormPolicy::class,

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
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        // $this->generalPolicies();
        //

        // Gate::define('to-do', function (User $user, $permission_name) {
        //     $checkpermission = UserRole::where('user_roles.user_id', $user->id)
        //             ->join('role_permissions', 'role_permissions.role_id', 'user_roles.role_id')
        //             ->join('permissions', 'permissions.id', 'role_permissions.permission_id')
        //             ->select('role_permissions.*')
        //             ->where('permissions.name', $permission_name)
        //             ->get();
            
        //     return $checkpermission != null; 
        // });
    }

}
